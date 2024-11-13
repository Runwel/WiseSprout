<?php
ob_start();
include 'includes/nav.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; 
} elseif ($isSemiVerified) {
    header("Location: resources.php");
    exit;
} elseif ($isAdmin) {
    header('Location: admin/dashboard.php');
    exit;
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Sprinkler Control Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body{
            background-size: cover;
            background-position: center;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1045%26quot%3b)' fill='none'%3e%3crect width='1440' height='560' x='0' y='0' fill='%230e2a47'%3e%3c/rect%3e%3cpath d='M1016.49 528.65 a150.31 150.31 0 1 0 300.62 0 a150.31 150.31 0 1 0 -300.62 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M309.278%2c123.934C333.379%2c124.263%2c355.494%2c110.836%2c367.576%2c89.98C379.69%2c69.07%2c380.667%2c43.002%2c368.133%2c22.341C356.019%2c2.371%2c332.633%2c-6.452%2c309.278%2c-6.121C286.557%2c-5.799%2c264.762%2c4.383%2c253.243%2c23.97C241.569%2c43.82%2c242.414%2c68.338%2c253.674%2c88.425C265.199%2c108.986%2c285.709%2c123.612%2c309.278%2c123.934' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M816.48 256.21 a120.91 120.91 0 1 0 241.82 0 a120.91 120.91 0 1 0 -241.82 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M175.68 203.68 a164.92 164.92 0 1 0 329.84 0 a164.92 164.92 0 1 0 -329.84 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M990.54 431.67 a104.48 104.48 0 1 0 208.96 0 a104.48 104.48 0 1 0 -208.96 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M-13.279%2c407.601C13.74%2c408.625%2c41%2c396.011%2c53.536%2c372.055C65.388%2c349.405%2c56.529%2c322.627%2c42.296%2c301.393C29.825%2c282.788%2c9.085%2c273.618%2c-13.279%2c272.377C-38.59%2c270.972%2c-67.363%2c272.996%2c-80.677%2c294.568C-94.424%2c316.843%2c-85.27%2c344.82%2c-71.864%2c367.303C-58.854%2c389.123%2c-38.665%2c406.639%2c-13.279%2c407.601' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1130.2 180.89 a176.07 176.07 0 1 0 352.14 0 a176.07 176.07 0 1 0 -352.14 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M26.78 491.74 a105.1 105.1 0 1 0 210.2 0 a105.1 105.1 0 1 0 -210.2 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M83.775%2c675.743C119.332%2c676.013%2c150.125%2c651.628%2c166.151%2c619.886C180.728%2c591.015%2c173.249%2c558.01%2c157.474%2c529.776C141.204%2c500.656%2c117.132%2c472.413%2c83.775%2c472.453C50.466%2c472.493%2c26.409%2c500.754%2c10.383%2c529.954C-4.908%2c557.816%2c-10.752%2c590.263%2c3.539%2c618.651C19.358%2c650.076%2c48.594%2c675.476%2c83.775%2c675.743' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M804.09 424.32 a140.99 140.99 0 1 0 281.98 0 a140.99 140.99 0 1 0 -281.98 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M606.94 96.57 a148.48 148.48 0 1 0 296.96 0 a148.48 148.48 0 1 0 -296.96 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1218.09 500.61 a161.61 161.61 0 1 0 323.22 0 a161.61 161.61 0 1 0 -323.22 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M958.04 401.42 a112.87 112.87 0 1 0 225.74 0 a112.87 112.87 0 1 0 -225.74 0z' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3cpath d='M1170.487%2c560.317C1218.182%2c557.336%2c1260.277%2c532.741%2c1285.619%2c492.226C1312.806%2c448.76%2c1327.559%2c394.266%2c1302.232%2c349.691C1276.675%2c304.711%2c1222.113%2c286.258%2c1170.487%2c289.593C1124.332%2c292.575%2c1090.574%2c326.293%2c1065.258%2c365C1036.262%2c409.334%2c1002.278%2c461.727%2c1027.404%2c508.363C1053.198%2c556.241%2c1116.209%2c563.709%2c1170.487%2c560.317' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float1'%3e%3c/path%3e%3cpath d='M322.72%2c582.303C342.395%2c582.456%2c359.631%2c570.686%2c369.911%2c553.909C380.732%2c536.249%2c386.726%2c513.595%2c375.477%2c496.204C364.821%2c479.73%2c342.28%2c479.422%2c322.72%2c480.951C306.317%2c482.233%2c291.376%2c489.586%2c282.547%2c503.469C272.952%2c518.557%2c268.665%2c537.252%2c276.784%2c553.184C285.606%2c570.497%2c303.29%2c582.152%2c322.72%2c582.303' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float2'%3e%3c/path%3e%3cpath d='M1318.585%2c355.618C1336.334%2c355.681%2c1350.406%2c342.517%2c1359.306%2c327.161C1368.238%2c311.751%2c1373.038%2c292.776%2c1363.855%2c277.515C1354.879%2c262.598%2c1335.994%2c259.411%2c1318.585%2c259.486C1301.347%2c259.561%2c1282.825%2c263.102%2c1273.99%2c277.904C1264.988%2c292.987%2c1269.623%2c311.637%2c1278.365%2c326.872C1287.156%2c342.191%2c1300.923%2c355.555%2c1318.585%2c355.618' fill='rgba(28%2c 83%2c 142%2c 0.4)' class='triangle-float3'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1045'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3c/defs%3e%3c/svg%3e");
        }
    </style>
</head>
<body>
<main role="main" class="mt-5">
<div class="container-fluid">
    <div class="weather-container row">
        <div class="col-md-6 row">
            <div id="weather-info" class="col-md-6">
                <div class="d-flex">
                    <h3>Current Weather   </h3><span id="weather-icon" style="margin: -5px 0 0 5px;"></span>
                </div>
                <h5 id="city-name">City: Loading...</h5>
                <h5 id="condition">Condition: Loading...</h5>
            </div>
            <div class="col-md-6">
                <h3>Weather API Data:</h3>
                <p id="temperature">Temperature: Loading...</p>
                <p id="humidity">Humidity: Loading...</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sensor-data">
                <h4>Soil Humidity Sensor</h4>
                <p>Current Soil Humidity: <span id="soil-humidity">Loading...</span></p>
                <p>Condition: <span id="soil-condition">Loading...</span></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="status-data">
                <h4>Arduino Uno R4 / Device Status</h4>
                <p>Status: <span id="device-status">Loading...</span></p>
                <p id="last-updated">Last Updated: Loading...</p>
            </div>
        </div>
    </div>
    <section class="row my-5">
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <canvas id="soilMoistureChart"></canvas>
            </div>
            <div id="soilMoistureDescription" class="chart-description"></div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <canvas id="scheduleComparisonChart"></canvas>
            </div>
            <div id="scheduleComparisonDescription" class="chart-description"></div>
        </div>
    </section>
    <div class="control-panel row">
        
        <div class="col-md-8 text-white">
            <h2 class="text-center">Schedules:</h2>
            <p class="text-center">M-Monday / T-Tuesday / W-Wednesday / TH-Thursday / F-Friday / S-Saturday / SU-Sunday</p>
            <div>
                <table class="table table-striped table-bordered text-white">
                <thead>
                    <tr>
                        <th class='text-center' style='width: 15%;'>Time</th>
                        <th class='text-center' style='width: 15%;'>Days</th>
                        <th class='text-center' style='width: 15%;'>Status</th>
                        <th class='text-center' style='width: 15%;'>Action</th>
                    </tr>
                </thead>
                <tbody id="schedule-table">
                    <!-- Table rows will be dynamically loaded here -->
                </tbody>
            </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="starter-template">
                <h1 class="text-center">Control Panel</h1>
                <p class="lead text-center">Set the time for the sprinkler to run:</p>
                <form id="add-schedule-form" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="add-id">
                            <div class="form-group">
                                <label for="add-time">Start Time:</label>
                                <input type="time" class="form-control" id="add-time" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="add-duration">Duration:</label>
                                <select class="form-control" id="add-duration" name="duration" required>
                                    <option value="180">3 minutes</option>
                                    <option value="300">5 minutes</option>
                                    <option value="600">10 minutes</option>
                                    <option value="1200">20 minutes</option>
                                    <option value="1800">30 minutes</option>
                                </select>
                            </div>
                            <div class="form-group" id="days-checkbox-group">
                                <label for="add-days">Select Days:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="monday" name="days[]" value="Monday">
                                    <label class="form-check-label" for="monday">Monday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                    <label class="form-check-label" for="tuesday">Tuesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                    <label class="form-check-label" for="wednesday">Wednesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="thursday" name="days[]" value="Thursday">
                                    <label class="form-check-label" for="thursday">Thursday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="friday" name="days[]" value="Friday">
                                    <label class="form-check-label" for="friday">Friday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="saturday" name="days[]" value="Saturday">
                                    <label class="form-check-label" for="saturday">Saturday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sunday" name="days[]" value="Sunday">
                                    <label class="form-check-label" for="sunday">Sunday</label>
                                </div>
                            </div>
                            <button type="submit align-self-left" class="btn btn-primary w-100">Set Schedule</button>
                        </div>
                    </form>
            </div>
        </div>
        </div>
        <!-- Modal for editing schedule -->
        <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-schedule-form" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="form-group">
                                <label for="edit-time">Start Time:</label>
                                <input type="time" class="form-control" id="edit-time" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-duration">Duration:</label>
                                <select class="form-control" id="edit-duration" name="duration" required>
                                    <option value="180">3 minutes</option>
                                    <option value="300">5 minutes</option>
                                    <option value="600">10 minutes</option>
                                    <option value="1200">20 minutes</option>
                                    <option value="1800">30 minutes</option>
                                </select>
                            </div>
                            <div class="form-group" id="edit-days-checkbox-group">
                                <label>Days:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-monday" name="days[]" value="Monday">
                                    <label class="form-check-label" for="edit-monday">Monday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-tuesday" name="days[]" value="Tuesday">
                                    <label class="form-check-label" for="edit-tuesday">Tuesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-wednesday" name="days[]" value="Wednesday">
                                    <label class="form-check-label" for="edit-wednesday">Wednesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-thursday" name="days[]" value="Thursday">
                                    <label class="form-check-label" for="edit-thursday">Thursday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-friday" name="days[]" value="Friday">
                                    <label class="form-check-label" for="edit-friday">Friday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-saturday" name="days[]" value="Saturday">
                                    <label class="form-check-label" for="edit-saturday">Saturday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="edit-sunday" name="days[]" value="Sunday">
                                    <label class="form-check-label" for="edit-sunday">Sunday</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Modal for displaying the response message -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Response</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-body" id="responseMessage"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
            </div>
        </div>
    </div>
</div>
</main>
<script>
    $(document).ready(function() {
    // Edit button click handler
    $(document).on('click', '.edit-btn', function() {
        var scheduleId = $(this).data('id');
        var startTime = $(this).data('time');
        var duration = $(this).data('duration');
        var daysData = $(this).data('days');

        $('#edit-id').val(scheduleId);
        $('#edit-time').val(startTime);
        $('#edit-duration').val(duration);

        // Clear all checkboxes first
        $('input[name="days[]"]').prop('checked', false);

        if (Array.isArray(daysData)) {
            daysData.forEach(function(day) {
                $('#edit-' + day.toLowerCase()).prop('checked', true);
            });
        } else {
            console.warn('Days data is not in the expected format:', daysData);
        }

        $('#editScheduleModal').modal('show');
    });

    // Delete button click handler
    $(document).on('click', '.delete-btn', function() {
        if (confirm('Are you sure you want to delete this schedule?')) {
            var scheduleId = $(this).data('id');

            fetch(`includes/delete_schedule.php?id=${scheduleId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
                $('#responseMessage').text(data.message);
                $('#responseModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                $('#responseMessage').text('An error occurred while deleting the schedule.');
                $('#responseModal').modal('show');
            });
        }
    });

    // Form submit handler
    function handleFormSubmit(formId, url) {
        document.getElementById(formId).addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var checkboxes = this.querySelectorAll('input[type="checkbox"]');
            var isChecked = Array.prototype.slice.call(checkboxes).some(function(checkbox) {
                return checkbox.checked;
            });

            if (!isChecked) {
                // If no checkbox is checked, show the modal with the specific message
                document.getElementById('responseMessage').textContent = 'Please select at least one day before submitting the form.';
                $('#responseModal').modal('show');
                return; // Prevent form submission
            }

            // Gather form data
            const formData = new FormData(this);

            // Send the form data using Fetch API
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Close the edit modal if it's the edit form
                if (formId === 'edit-schedule-form') {
                    $('#editScheduleModal').modal('hide');
                }

                // Display the response message in the response modal
                document.getElementById('responseMessage').textContent = data.message;
                $('#responseModal').modal('show');

                // If the update was successful, refresh the table
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                // Handle any errors
                document.getElementById('responseMessage').textContent = 'An error occurred: ' + error;
                $('#responseModal').modal('show');
            });
        });
    }

    handleFormSubmit('add-schedule-form', 'includes/set_schedule.php');
    handleFormSubmit('edit-schedule-form', 'includes/update_schedule.php');

    function refreshTable() {
        fetch('includes/fetch_schedule_table.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('schedule-table').innerHTML = data;
            });
    }
    refreshTable();
    setInterval(refreshTable, 5000);

    // Function to update the status of a schedule
    function updateStatus(scheduleId, newStatus) {
        fetch('includes/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: scheduleId, status: newStatus })
        }).then(response => response.json())
        .then(data => {
            // Handle any actions if needed after updating status
            // console.log(`Updated status for schedule ${scheduleId}:`, data);
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
    }

    // Function to check and update schedule statuses
    function updateScheduleStatus() {
        fetch('includes/fetch_status.php')  // Fetch schedules
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var current_time = new Date();  // Get current time

                    if (Array.isArray(data.schedules) && data.schedules.length > 0) {
                        // Iterate over each schedule and update status
                        data.schedules.forEach(function(schedule) {
                            updateSchedule(schedule, current_time);
                        });
                    } else if (!Array.isArray(data.schedules) && typeof data.schedules === 'object') {
                        // Handle case where there's a single schedule object
                        updateSchedule(data.schedules, current_time);
                    }
                } else {
                    console.error('Failed to fetch schedules');
                }
            })
            .catch(error => {
                console.error('Error fetching schedules:', error);
            });
    }

    // Function to update status for each individual schedule
    function updateSchedule(schedule, current_time) {
        if (!schedule || !schedule.start_time || !schedule.duration || !schedule.days || !schedule.status) {
            return;  // If schedule data is invalid, skip it
        }

        // Convert start_time and calculate end_time as Date objects for comparison
        var start_time_parts = schedule.start_time.split(':');
        var start_time = new Date();
        start_time.setHours(start_time_parts[0]);
        start_time.setMinutes(start_time_parts[1]);
        start_time.setSeconds(start_time_parts[2]);

        var end_time = new Date(start_time);
        end_time.setSeconds(end_time.getSeconds() + schedule.duration);  // Add duration to start time

        // Current day details
        var currentDayStart = new Date();
        currentDayStart.setHours(0, 0, 0, 0);  // Start of current day
        var currentDayEnd = new Date(currentDayStart);
        currentDayEnd.setHours(23, 59, 59, 999);  // End of current day

        var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var currentDay = daysOfWeek[current_time.getDay()];

        // Split the schedule days and check if today is scheduled
        var scheduleDays = schedule.days.split(',');
        var isTodayScheduled = scheduleDays.includes(currentDay);

        if (isTodayScheduled) {
            var statusToUpdate = '';

            if (current_time >= start_time && current_time <= end_time) {
                // If the current time is within the schedule's start and end time, mark as Loading
                if (schedule.status !== 'Ongoing') {
                    statusToUpdate = 'Loading....';
                }
            } else if (current_time > end_time && schedule.status === 'Loading....') {
                // If the schedule has passed its end time and is still Loading, change to Device Connected
                statusToUpdate = 'Device Disconnected';
            }

            // Update status if it has changed
            if (statusToUpdate && statusToUpdate !== schedule.status) {
                updateStatus(schedule.id, statusToUpdate);
            }
        }
    }
    // Call the function to start updating the schedule statuses
    updateScheduleStatus();
    setInterval(updateScheduleStatus, 5000);

    // Weather API fetch
    fetch('api/weather.php')
    .then(response => response.json())
    .then(data => {
        // Check if there's an error in the response
        if (data.error) {
            console.error('Error:', data.error);
            return;
        }

        // Extract weather data from the response
        const location = data.location;
        const currentWeather = data.current;

        const city = location.name;  // City name
        const temperature = currentWeather.temp_c;  // Temperature in Celsius
        const humidity = currentWeather.humidity;
        const conditionText = currentWeather.condition.text;  // Condition text like "Partly cloudy"
        const iconUrl = `https:${currentWeather.condition.icon}`;  // Weather icon URL

        // Update the HTML with weather information
        document.getElementById('city-name').innerText = `City: ${city}`;
        document.getElementById('temperature').innerText = `Temperature: ${temperature} °C`;
        document.getElementById('humidity').innerText = `Humidity: ${humidity}%`;
        document.getElementById('condition').innerText = `Condition: ${conditionText}`;

        // Determine the correct FontAwesome icon class based on weather condition
        let iconClass = '';
        switch (conditionText.toLowerCase()) {
            case 'clear':
            case 'sunny':
            case 'mostly clear':
                iconClass = 'fa-sun text-warning'; // Sun icon for clear/sunny weather
                break;
            case 'partly cloudy':
                iconClass = 'fa-solid fa-cloud-sun text-primary'; // Partly cloudy
                break;
            case 'cloudy':
                iconClass = 'fa-cloud text-dark'; // Cloudy weather
                break;
            case 'rainy':
            case 'showers':
                iconClass = 'fa-cloud-showers-heavy text-info'; // Rainy weather
                break;
            case 'snow':
                iconClass = 'fa-snowflake text-light'; // Snowy weather
                break;
            default:
                iconClass = 'fa-question-circle text-muted'; // Default icon for unknown conditions
        }

        // Set the FontAwesome icon class
        setWeatherIcon(iconClass);
    })
    .catch(error => console.error('Error fetching weather data:', error));

    function setWeatherIcon(iconClass) {
        const weatherIconDiv = document.getElementById('weather-icon');
        weatherIconDiv.innerHTML = `<i class="fas ${iconClass} fa-3x"></i>`;
    }

    // Soil sensor chart
    var ctx = document.getElementById('soilMoistureChart').getContext('2d');
    var soilMoistureData = [65, 68, 70, 72, 69, 75]; // Data for Monday to Saturday
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: 'Soil Moisture Level',
                data: soilMoistureData,
                borderColor: 'white', // Set line color to white
                backgroundColor: 'rgba(0, 0, 255, 0.1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    color: 'white', // X-axis label color
                    ticks: {
                        color: 'white' // X-axis tick color
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // X-axis grid lines color
                    }
                },
                y: {
                    beginAtZero: true,
                    color: 'white', // Y-axis label color
                    ticks: {
                        color: 'white' // Y-axis tick color
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // Y-axis grid lines color
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Legend text color
                    }
                },
                tooltip: {
                    callbacks: {
                        labelColor: function(context) {
                            return { borderColor: 'white', backgroundColor: 'white' };
                        }
                    }
                }
            }
        }
    });
    document.getElementById('soilMoistureDescription').innerHTML = interpretSoilMoisture(soilMoistureData);

    function interpretSoilMoisture(data) {
        let trend = data[data.length - 1] > data[0] ? "increasing" : "decreasing";
        let avgMoisture = data.reduce((a, b) => a + b, 0) / data.length;
        let conclusion = avgMoisture > 70 ? "The soil moisture is generally good." : "The soil might need more frequent watering.";
        return `The soil moisture shows a ${trend} trend over the past 6 days. The average moisture level is ${avgMoisture.toFixed(1)}%. ${conclusion}`;
    }

    // Schedule Comparison Chart
    function createScheduleComparisonChart() {
        var ctx3 = document.getElementById('scheduleComparisonChart').getContext('2d');

        // Fetch schedule data from PHP endpoint
        fetch('includes/fetch_schedules.php')
            .then(response => response.json())  // Parse the JSON response
            .then(data => {
                // Prepare the data for the chart
                const scheduleComparisonData = prepareScheduleData(data);

                // Create the chart with the fetched data
                var scheduleComparisonChart = new Chart(ctx3, {
                    type: 'bar',
                    data: {
                        labels: scheduleComparisonData.labels,
                        datasets: scheduleComparisonData.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                                color: 'white',
                                ticks: {
                                    color: 'white'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                max: 60,
                                ticks: {
                                    stepSize: 10,
                                    color: 'white'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Weekly Watering Schedule',
                                color: 'white'
                            },
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) label += ': ';
                                        if (context.parsed.y !== null) label += context.parsed.y + ' minutes';
                                        let dayData = scheduleComparisonData.rawData[context.dataIndex];
                                        let scheduleData = dayData.schedules[context.datasetIndex];
                                        if (scheduleData) label += ` (${scheduleData.time})`;
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });

                // Display the description
                document.getElementById('scheduleComparisonDescription').innerHTML = interpretScheduleComparison(scheduleComparisonData.rawData);

            })
            .catch(error => console.error('Error fetching schedule data:', error));

        // Function to prepare the schedule data for charting
        function prepareScheduleData(data) {
            const dayOrder = ['M', 'T', 'W', 'TH', 'F', 'S', 'SU'];  // Days in correct order
            const labels = [];
            const datasets = [];
            const maxSchedules = Math.max(...Object.values(data).map(day => day.length)); // Get the maximum number of schedules for any day
            const rawData = [];

            // Loop through the dayOrder to structure data based on M, T, W, etc.
            dayOrder.forEach(day => {
                labels.push(day); // Set day as label in correct order
                const schedules = data[day] || [];  // If no data for the day, default to empty array
                rawData.push({ day: day, schedules: schedules });

                // Create datasets for each watering time
                for (let i = 0; i < maxSchedules; i++) {
                    if (!datasets[i]) {
                        datasets[i] = {
                            label: `Watering ${i + 1}`,
                            data: [],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                            ][i % 3],
                            borderColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 206, 86)',
                            ][i % 3],
                            borderWidth: 1
                        };
                    }
                    // Convert duration from seconds to minutes
                    datasets[i].data.push(schedules[i] ? (schedules[i].duration / 60) : 0); // Duration is in minutes
                }
            });

            return { labels, datasets, rawData };
        }

        // Function to generate schedule comparison interpretation
        function interpretScheduleComparison(data) {
            // Convert all durations to minutes for proper calculation and display
            let totalDurationInMinutes = 0;
            let dayCount = 0;

            // To store the total duration per day for calculating the highest and lowest
            let maxDay = { day: '', total: 0 };
            let minDay = { day: '', total: Infinity };

            // Loop through the data to calculate the total duration, and track max and min
            data.forEach(day => {
                // Only process days that have watering schedules
                if (day.schedules.length > 0) {
                    let dayTotalInMinutes = day.schedules.reduce((sum, schedule) => sum + (schedule.duration / 60), 0); // convert to minutes
                    totalDurationInMinutes += dayTotalInMinutes;
                    dayCount++;

                    if (dayTotalInMinutes > maxDay.total) {
                        maxDay = { day: day.day, total: dayTotalInMinutes };
                    }

                    if (dayTotalInMinutes < minDay.total) {
                        minDay = { day: day.day, total: dayTotalInMinutes };
                    }
                }
            });

            // If no schedules were found, return a default message
            if (dayCount === 0) {
                return "No watering schedules found for the selected days.";
            }

            // Calculate average watering duration
            let avgDuration = (totalDurationInMinutes / dayCount).toFixed(1);  // avg calculated on actual days with watering

            // Build the description message with the updated values
            return `Avg daily watering: ${avgDuration} min. Highest: ${maxDay.day} (${maxDay.total} min). 
                    Lowest: ${minDay.day} (${minDay.total} min). 
                    ${totalDurationInMinutes > 120 ? "Consider reducing overall watering time." : "Schedule seems efficient."}`;
        }
    }

    // Call the function to create the chart when the page loads
    createScheduleComparisonChart();
    });

    function fetchSoilData() {
        const userId = <?php echo json_encode($user_id); ?>;
        fetch('latest-data.php?user_id=' + userId)
            .then(response => response.json())
            .then(data => {
                // Update soil data
                document.getElementById('soil-humidity').innerText = data.moisture + "%";
                document.getElementById('soil-condition').innerText = data.condition;

                // Get the current time and the last updated timestamp from the response
                const currentTime = new Date();
                const lastUpdatedTime = new Date(data.timestamp);

                // Compare the current time with the last updated time
                const timeDifference = (currentTime - lastUpdatedTime) / 1000; // Time difference in seconds

                // Set device status based on time difference
                if (timeDifference < 60) {
                    // If the timestamp is within the last minute, show Online
                    document.getElementById('device-status').innerText = data.status === "Online" ? "Online" : "Offline";
                } else {
                    // If the timestamp is older than 1 minute, show Offline
                    document.getElementById('device-status').innerText = "Offline";
                }

                // Update last updated time on the page
                document.getElementById('last-updated').innerText = "Last Updated: " + data.timestamp;
            })
            .catch(error => console.error('Error fetching data:', error));
    }
    // Fetch data every 60 seconds
    setInterval(fetchSoilData, 20000);
    fetchSoilData(); // Initial fetch
</script>
<?php include'includes/footer.php' ?>
</body>
</html>