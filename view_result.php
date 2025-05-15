<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchResults() {
            $.ajax({
                url: 'get_results.php',
                method: 'GET',
                success: function(data) {
                    var grouped = JSON.parse(data);
                    var resultContainer = $('#results-list');
                    resultContainer.empty();

                    const positions = ["President", "Vice President", "Secretary", "Treasurer", "Auditor"];

                    positions.forEach(function(position) {
                        if (grouped[position]) {
                            var section = $('<div class="mb-4"></div>');
                            section.append('<h5 class="mb-2 text-success">' + position + '</h5>');
                            var list = $('<ul class="list-group"></ul>');

                            grouped[position].forEach(function(candidate) {
                                list.append(
                                    '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                                        candidate.name +
                                        '<span class="badge bg-primary rounded-pill">' + candidate.votes + ' votes</span>' +
                                    '</li>'
                                );
                            });

                            section.append(list);
                            resultContainer.append(section);
                        }
                    });
                }
            });
        }

        setInterval(fetchResults, 5000);
        $(document).ready(function() {
            fetchResults();
        });
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Election Results</h4>
                <div id="results-list">
                    <!-- Dynamic results by position will load here -->
                </div>
                <div class="mt-4 text-center">
                    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
