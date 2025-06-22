<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="<?php echo base_url(); ?>public/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script type="text/javascript">
$(function() {
    $(this).bind("contextmenu", function(e) {
        e.preventDefault();
    });
});
</script>
<script type="text/JavaScript">
    function killCopy(e){ return false }
    function reEnable(){ return true }
    document.onselectstart=new Function ("return false");
    if (window.sidebar)
    {
        document.onmousedown=killCopy;
        document.onclick=reEnable;
    }
</script>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
<!-- CSRF token -->
<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
    <!-- MUST BE REMOVED ON DEPLOYMENT (AUTO REFRESH) 
    <meta http-equiv="refresh" content="10"/>       -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>public/assets/images/favicon.png">
    <title>Harbour Management</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/assets/libs/select2/dist/css/select2.min.css">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/dist/css/style.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>public/latestweight/files/main.css" rel="stylesheet">
    <!-- Magnify popup (image) -->
    <link href="<?php echo base_url(); ?>public/assets/libs/magnific-popup/dist/magnific-popup.css" rel="stylesheet">


    
    <!--   <script src="<?php echo base_url(); ?>public/assets/libs/jquery/dist/jquery.min.js"></script>-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <script src="<?php echo base_url(); ?>public/assets/libs/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap tether Core JavaScript -->

   
</script>

    <!-- Bootstrap tether Core JavaScript -->

    <style type="text/css">
        .headertitle {
            background: black;
            color: white;
            text-align: center;
            font-size: large;
            font-weight: bold;
        }
    </style>
    <!-- CSRF meta tags for global JS access -->
    <?php if (isset($this->security)) { ?>
        <meta name="csrf-token-name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
        <meta name="csrf-token-hash" content="<?php echo $this->security->get_csrf_hash(); ?>">
    <?php } ?>
    <script>
    // Global AJAX CSRF token injection for jQuery and fetch
    (function() {
        var csrfName = document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
        var csrfHash = document.querySelector('meta[name="csrf-token-hash"]').getAttribute('content');

        // jQuery global setup
        if (window.jQuery) {
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    if (settings.type === 'POST' || settings.type === 'PUT' || settings.type === 'DELETE') {
                        if (settings.data && typeof settings.data === 'string') {
                            settings.data += '&' + encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfHash);
                        } else if (settings.data && typeof settings.data === 'object') {
                            settings.data[csrfName] = csrfHash;
                        } else {
                            settings.data = csrfName + '=' + csrfHash;
                        }
                    }
                }
            });
        }

        // fetch API global wrapper
        var _fetch = window.fetch;
        window.fetch = function(input, init) {
            init = init || {};
            if (init.method && ['POST','PUT','DELETE'].includes(init.method.toUpperCase())) {
                if (init.headers instanceof Headers) {
                    init.headers.append(csrfName, csrfHash);
                } else {
                    init.headers = Object.assign({}, init.headers || {}, { [csrfName]: csrfHash });
                }
            }
            return _fetch(input, init);
        };
    })();
    </script>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
      <div id="main-wrapper" class="toggled mini-sidebar">
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
               <div class="navbar-header" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="#">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <span style="color:whitesmoke; font-size:20px;"><i class="ti-anchor"></i> </span>

                        </b>
                        <!--End Logo icon -->
                         <!-- Logo text -->
                        <span class="logo-text">
                             <!-- dark Logo text -->

                            Harbour Engineering
                        </span>

                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->

                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item" style="margin: auto;">
                            <div class="text-white">
                                <?php echo $userinfo->name.' '.$userinfo->designation.' '.$userinfo->fullname; ?>
                                </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url(); ?>public/assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
<!--                             <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=base_url('logout')?>"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                            </div> -->
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
