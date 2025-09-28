<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 5 Debug Test</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-primary">
                    <i class="bi bi-check-circle"></i> Bootstrap 5 Test Page
                </h1>
                <p class="lead">If you can see this styled properly, Bootstrap 5 is working!</p>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Test Card</h5>
                    </div>
                    <div class="card-body">
                        <p>This is a Bootstrap 5 card component.</p>
                        <button class="btn btn-primary">Primary Button</button>
                        <button class="btn btn-success">Success Button</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> This is a Bootstrap 5 alert component.
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> This is a warning alert.
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h3>Bootstrap Components Test</h3>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Component</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CSS Framework</td>
                            <td><span class="badge bg-success">Loaded</span></td>
                            <td>Bootstrap 5.3.0</td>
                        </tr>
                        <tr>
                            <td>Icons</td>
                            <td><span class="badge bg-success">Loaded</span></td>
                            <td>Bootstrap Icons 1.10.0</td>
                        </tr>
                        <tr>
                            <td>Grid System</td>
                            <td><span class="badge bg-success">Working</span></td>
                            <td>12-column grid</td>
                        </tr>
                        <tr>
                            <td>Components</td>
                            <td><span class="badge bg-success">Working</span></td>
                            <td>Cards, buttons, alerts</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h3>JavaScript Test</h3>
                <button class="btn btn-primary" onclick="testBootstrapJS()">Test Bootstrap JS</button>
                <div id="jsTestResult" class="mt-2"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function testBootstrapJS() {
            const resultDiv = document.getElementById('jsTestResult');
            
            // Test if Bootstrap JS is loaded
            if (typeof bootstrap !== 'undefined') {
                resultDiv.innerHTML = '<div class="alert alert-success">Bootstrap JS is loaded and working!</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">Bootstrap JS is not loaded!</div>';
            }
        }
        
        // Auto-run test on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Bootstrap Debug Page Loaded');
            console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'Loaded' : 'Not loaded');
        });
    </script>
</body>
</html>
