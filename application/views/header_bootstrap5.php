<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>public/assets/images/favicon.png">
    
    <title>Harbour Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/dist/css/style.min.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/assets/libs/select2/dist/css/select2.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Magnify popup CSS -->
    <link href="<?php echo base_url(); ?>public/assets/libs/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    
    <!-- jQuery (load before Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- CSRF Protection -->
    <meta name="csrf-token" content="<?= $this->security->get_csrf_hash(); ?>">
    
    <style>
        .headertitle {
            background: #007bff;
            color: white;
            text-align: center;
            font-size: large;
            font-weight: bold;
            padding: 10px;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .page-wrapper {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-anchor"></i> Harbour Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('section') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('agreement') ?>">Agreements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('trip') ?>">Trips</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="page-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('section') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chainage Management</li>
                </ol>
            </nav>
