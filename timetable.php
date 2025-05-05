<?php
// Sample timetable data
// Logic to switch between daily and weekly views
$view = isset($_GET['view']) ? $_GET['view'] : 'daily';
$currentDay = isset($_GET['day']) ? $_GET['day'] : 'Monday';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="row text-center">
  <!-- Class Timetable Block -->
  <div class="col-md-4 mb-4">
    <div onclick="loadPDF('uploads/classTimetable.pdf')" class="card text-white" style="background-color: #0d47a1; height: 150px; cursor: pointer;">
      <div class="card-body d-flex flex-column justify-content-center">
        <div class="mb-2" style="font-size: 30px;">📚</div>
        <h5 class="card-title">Class Timetable</h5>
      </div>
    </div>
  </div>

  <!-- Semester Test Timetable Block -->
  <div class="col-md-4 mb-4">
    <div onclick="loadPDF('uploads/semesterTest.pdf')" class="card text-white" style="background-color: #2196f3; height: 150px; cursor: pointer;">
      <div class="card-body d-flex flex-column justify-content-center">
        <div class="mb-2" style="font-size: 30px;">📝</div>
        <h5 class="card-title">Semester Test Timetable</h5>
      </div>
    </div>
  </div>

  <!-- Exam Timetable Block -->
  <div class="col-md-4 mb-4">
    <div onclick="loadPDF('uploads/examTimetable.pdf')" class="card text-white" style="background-color: #64b5f6; height: 150px; cursor: pointer;">
      <div class="card-body d-flex flex-column justify-content-center">
        <div class="mb-2" style="font-size: 30px;">🎓</div>
        <h5 class="card-title">Exam Timetable</h5>
      </div>
    </div>
  </div>
</div>

<div class="mt-5">
  <div class="card">
    <div class="card-header text-center">
      <h4>📄 Timetable Preview</h4>
    </div>
    <div class="card-body">
      <iframe id="pdfViewer" src="" frameborder="0" width="100%" height="600px"></iframe>
    </div>
  </div>
</div>



<!-- Bootstrap JS and dependencies -->


</body>

<script>
  function loadPDF(pdfPath) {
    const pdfViewer = document.getElementById('pdfViewer');
    pdfViewer.src = pdfPath;
  }
</script>

</html>
