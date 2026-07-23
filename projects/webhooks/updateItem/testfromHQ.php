<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Cards</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .card {
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row" id="cards-container">
            <!-- Cards will be dynamically inserted here -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="eventForm" action="./newUpdateItem.php" method="POST">
              <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="summary" class="form-label">Summary</label>
                      <input type="text" class="form-control" id="summary" name="summary">
                  </div>
                  <!-- Extended Properties Inputs will be inserted here -->
                  <div id="extendedProperties">
                      <!-- Dynamic Inputs -->
                  </div>
                  <input type="hidden" id="eventId" name="eventId">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Sample JSON Data
      eventsData = [];
      $.get('https://ammons.dev/projects/webhooks/receive/send.php', function (data) {
            eventsData = data.bookedTimes;
            initCards();
        });
      
        // Function to format date
        function formatDate(dateTimeStr, timeZone) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZone: timeZone,
                timeZoneName: 'short'
            };
            const date = new Date(dateTimeStr);
            return date.toLocaleString(undefined, options);
        }

        // Function to create a card
        function createCard(event) {
            const col = document.createElement('div');
            col.className = 'col-md-4';

            const card = document.createElement('div');
            card.className = 'card h-100';
            card.setAttribute('data-event-id', event.id);

            const cardBody = document.createElement('div');
            cardBody.className = 'card-body';

            const cardTitle = document.createElement('h5');
            cardTitle.className = 'card-title';
            cardTitle.textContent = event.summary;

            const cardDate = document.createElement('h6');
            cardDate.className = 'card-subtitle mb-2 text-muted';
            const startDate = event.start.dateTime || event.start.date;
            cardDate.textContent = formatDate(startDate, event.start.timeZone);

            cardBody.appendChild(cardTitle);
            cardBody.appendChild(cardDate);
            card.appendChild(cardBody);
            col.appendChild(card);

            // Add click event to open modal
            card.addEventListener('click', () => openModal(event));

            return col;
        }

        // Function to open modal and populate form
        function openModal(event) {
            // Set Summary
            document.getElementById('summary').value = event.summary;

            // Set Event ID
            document.getElementById('eventId').value = event.id;

            // Clear previous extended properties
            const extendedPropertiesDiv = document.getElementById('extendedProperties');
            extendedPropertiesDiv.innerHTML = '';

            // Populate extended properties
            const privateProps = event.extendedProperties && event.extendedProperties.private;
            if (privateProps) {
                for (const [key, value] of Object.entries(privateProps)) {
                    const formGroup = document.createElement('div');
                    formGroup.className = 'mb-3';

                    const label = document.createElement('label');
                    label.className = 'form-label';
                    label.setAttribute('for', key);
                    label.textContent = key.charAt(0).toUpperCase() + key.slice(1);

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control';
                    input.id = key;
                    input.name = key;
                    input.value = value;
                    input.readOnly = false; // Make inputs read-only

                    formGroup.appendChild(label);
                    formGroup.appendChild(input);
                    extendedPropertiesDiv.appendChild(formGroup);
                }
            }

            // Show Modal
            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        }

        // Function to initialize cards
        function initCards() {
            const container = document.getElementById('cards-container');
            eventsData.forEach(event => {
                const card = createCard(event);
                container.appendChild(card);
            });
        }

        // Handle form submission
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            // e.preventDefault();
            // // You can handle form submission here
            // // For demonstration, we'll just log the form data
            // const formData = new FormData(this);
            // const data = {};
            // formData.forEach((value, key) => {
            //     data[key] = value;
            // });
            // console.log('Form Submitted:', data);
            // Close the modal after submission
            const eventModal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
            eventModal.hide();
        });

        // Initialize cards on page load
        //window.onload = initCards;
    </script>
</body>
</html>
