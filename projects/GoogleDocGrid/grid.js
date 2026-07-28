function normalizeDocUrl(url) {
  const trimmed = url.trim();
  if (!trimmed.includes('/pub')) {
    return trimmed.replace(/\/?$/, '/pub');
  }
  return trimmed;
}

async function fetchGoogleDocHtml(url) {
  const docUrl = normalizeDocUrl(url);

  try {
    const response = await fetch(docUrl);
    if (response.ok) {
      return response.text();
    }
  } catch (_) {
    // Direct fetch blocked by CORS — fall through to proxy.
  }

  const proxyUrl = `https://api.allorigins.win/raw?url=${encodeURIComponent(docUrl)}`;
  const proxyResponse = await fetch(proxyUrl);
  if (!proxyResponse.ok) {
    throw new Error(`Failed to fetch document (${proxyResponse.status})`);
  }
  return proxyResponse.text();
}

function parseCoordinateTable(html) {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  const tables = doc.querySelectorAll('table');

  let targetTable = null;
  for (const table of tables) {
    if (table.textContent.toLowerCase().includes('x-coordinate')) {
      targetTable = table;
      break;
    }
  }

  if (!targetTable) {
    throw new Error('Could not find coordinate table in document');
  }

  const rows = targetTable.querySelectorAll('tr');
  const points = [];

  for (const row of rows) {
    const cells = row.querySelectorAll('td');
    if (cells.length < 3) continue;

    const xText = cells[0].textContent.trim();
    const char = cells[1].textContent.trim();
    const yText = cells[2].textContent.trim();

    if (xText.toLowerCase() === 'x-coordinate' || !/^\d+$/.test(xText) || !/^\d+$/.test(yText)) {
      continue;
    }

    points.push({
      x: parseInt(xText, 10),
      y: parseInt(yText, 10),
      char,
    });
  }

  if (points.length === 0) {
    throw new Error('No coordinate data found in table');
  }

  return points;
}

function buildCharacterGrid(points) {
  let maxX = 0;
  let maxY = 0;

  for (const { x, y } of points) {
    if (x > maxX) maxX = x;
    if (y > maxY) maxY = y;
  }

  const grid = Array.from({ length: maxY + 1 }, () =>
    Array(maxX + 1).fill(' ')
  );

  for (const { x, y, char } of points) {
    grid[y][x] = char;
  }

  const lines = [];
  for (let y = maxY; y >= 0; y--) {
    lines.push(grid[y].join(''));
  }

  return lines.join('\n');
}

export async function printGridFromGoogleDoc(url) {
  const html = await fetchGoogleDocHtml(url);
  const points = parseCoordinateTable(html);
  const grid = buildCharacterGrid(points);
  console.log(grid);
  return grid;
}
