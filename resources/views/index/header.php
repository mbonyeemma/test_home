<header class="main-header"> 
    
    <!-- Logo --> 
    <a href="{{ url('/') }}" class="logo"> 
    <!-- mini logo for sidebar mini 50x50 pixels --> 
    <span class="logo-mini"><b>UNHLS</b></span> 
    <!-- logo for regular state and mobile devices --> 
    <span class="logo-lg"><b>UNHLS</b></span> </a> 
    
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation"> 
      <!-- Sidebar toggle button--> 
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> <span class="sr-only">Toggle navigation</span> </a> 
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account Menu -->
          <li class="dropdown notifications-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
            <?php if (Auth::check()) {
               echo Auth::user()->name;
            } ?> <span class="caret"></span> </a>
            <ul class="dropdown-menu">
              <li> 
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li> <a href="<?php echo url('/logout') ?>"> <i class="fa  fa-sign-out text-aqua"></i> Logout</a>
                    
                  </li>
                  <li> <a href="
                  <?php
if (Auth::check()) {
    echo url('user/resetpassword', ['id' => Auth::user()->id]);
}
?>
                  "> <i class="fa fa-user text-yellow"></i> Reset Password </a> </li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>