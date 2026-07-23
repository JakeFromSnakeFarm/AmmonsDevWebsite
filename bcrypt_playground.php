<?php
class BcryptPlayground {
    public static function make(string $password, ?string $salt = null, int $cost = 10): string {
        $defaultSalt = '3vfYtD8vrHZapi5haxL/c.';
        $salt = $salt ?? $defaultSalt;
        if (strlen($salt) !== 22 || preg_match('/[^\.\/A-Za-z0-9]/', $salt)) {
            throw new InvalidArgumentException('Salt must be exactly 22 chars from the bcrypt alphabet ./0-9A-Za-z');
        }
        $saltPrefix = sprintf('$2y$%02d$%s', $cost, $salt);
        return crypt($password, $saltPrefix);
    }
}

$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salt = $_POST['salt'] ?? '3vfYtD8vrHZapi5haxL/c.';
    $cost = (int)($_POST['cost'] ?? 10);

    // Generate hashes for money9000–money9999
    for ($i = 9000; $i <= 9999; $i++) {
        $pw = "SVP@ss10";
        $hash = BcryptPlayground::make($pw, $salt, $cost);
        $results[] = ['pw' => $pw, 'hash' => $hash];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bcrypt Playground — money9xxx Generator</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
form, .result { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px #ccc; margin-bottom: 20px; }
button { padding: 10px 15px; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; }
button:hover { background: #0056b3; }
code { background: #eee; padding: 4px 6px; border-radius: 4px; display: inline-block; }
table { border-collapse: collapse; width: 100%; font-family: monospace; font-size: 0.9em; }
th, td { text-align: left; padding: 6px; border-bottom: 1px solid #ddd; }
th { background: #fafafa; position: sticky; top: 0; }
.result { max-height: 80vh; overflow-y: scroll; }
</style>
</head>
<body>

<h2>Bcrypt Playground — money9000 to money9999</h2>
<form method="POST">
  <label>Salt (22 chars):</label>
  <input type="text" name="salt" style="width:100%" value="<?= htmlspecialchars($_POST['salt'] ?? '3vfYtD8vrHZapi5haxL/c.') ?>">
  <label>Cost (4–31):</label>
  <input type="number" name="cost" min="4" max="31" value="<?= htmlspecialchars($_POST['cost'] ?? 10) ?>">
  <br><br>
  <button type="submit">Generate 1000 Hashes (money9000–money9999)</button>
</form>

<?php if ($results): ?>
<div class="result">
  <h3>Generated Hashes (Fixed Salt)</h3>
  <table>
    <tr><th>Password</th><th>Hash</th></tr>
    <?php foreach ($results as $r): ?>
      <tr><td><?= htmlspecialchars($r['pw']) ?></td><td><code><?= htmlspecialchars($r['hash']) ?></code></td></tr>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>

</body>
</html>
