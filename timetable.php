<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Timetable</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      cursor: pointer;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="row text-center">
    <!-- Class Timetable Block -->
    <div class="col-md-4 mb-4">
      <div class="card text-white" style="background-color: #0d47a1;" onclick="loadPDF('uploads/classTimetable.pdf')">
        <div class="card-body d-flex flex-column justify-content-center" style="height: 150px;">
          <div class="mb-2" style="font-size: 30px;">📚</div>
          <h5 class="card-title">Class Timetable</h5>
        </div>
      </div>
    </div>

    <!-- Semester Test Timetable Block -->
    <div class="col-md-4 mb-4">
      <div class="card text-white" style="background-color: #2196f3;" onclick="loadPDF('uploads/semesterTest.pdf')">
        <div class="card-body d-flex flex-column justify-content-center" style="height: 150px;">
          <div class="mb-2" style="font-size: 30px;">📝</div>
          <h5 class="card-title">Semester Test Timetable</h5>
        </div>
      </div>
    </div>

    <!-- Exam Timetable Block -->
    <div class="col-md-4 mb-4">
      <div class="card text-white" style="background-color: #64b5f6;" onclick="loadPDF('uploads/examTimetable.pdf')">
        <div class="card-body d-flex flex-column justify-content-center" style="height: 150px;">
          <div class="mb-2" style="font-size: 30px;">🎓</div>
          <h5 class="card-title">Exam Timetable</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- PDF Viewer -->
  <div class="card mt-4">
    <div class="card-header text-center">
      <h4>📄 Timetable Preview</h4>
    </div>
    <div class="card-body">
      <iframe id="pdfViewer" src="" width="100%" height="600px" frameborder="0"></iframe>
      <div class="text-end mt-3">
        <a id="downloadBtn" href="#" class="btn btn-primary" target="_blank" style="display: none;">Download PDF</a>
      </div>
    </div>
  </div>
</div>

<!-- Script to load PDF -->
<script>
  function loadPDF(pdfPath) {
    // Check if file exists first
    fetch(pdfPath)
      .then(response => {
        if (!response.ok) throw new Error('File not found');
        document.getElementById('pdfViewer').src = pdfPath;
        const downloadBtn = document.getElementById('downloadBtn');
        downloadBtn.href = pdfPath;
        downloadBtn.style.display = 'inline-block';
      })
      .catch(() => {
        alert("❌ PDF file not found.");
        document.getElementById('pdfViewer').src = '';
        document.getElementById('downloadBtn').style.display = 'none';
      });
  }
</script>

</body>
</html>
