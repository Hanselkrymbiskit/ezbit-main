<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="margin-top:70px">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><sup>Site Admin</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo site_url('administrator/dashboard'); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        User Management
      </div>
      <!-- Nav Item - User Management Menu -->
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>User Account</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#accountConfiguration_Collapse" aria-expanded="true" aria-controls="accountConfiguration_Collapse">
          <i class="fas fa-fw fa-cog"></i>
          <span>Account Configuration</span>
        </a>
        <div id="accountConfiguration_Collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Account Configuration :</h6>
            <a class="collapse-item" href="buttons.html">User Type</a>
            <a class="collapse-item" href="cards.html">User Profile</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        System Management
      </div>
      <!-- Nav Item - User Management Menu -->
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-database"></i>
          <span>Database Management</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#systemSettings_Collapse" aria-expanded="true" aria-controls="accountConfiguration_Collapse">
          <i class="fas fa-fw fa-cog"></i>
          <span>System Settings</span>
        </a>
        <div id="systemSettings_Collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">System Settings :</h6>
            <a class="collapse-item" href="buttons.html">General Settings</a>
            <a class="collapse-item" href="cards.html">Security Settings</a>
            <a class="collapse-item" href="cards.html">Email Settings</a>
          </div>
        </div>
      </li>
      <!-- Heading -->
      <div class="sidebar-heading">
        Utilities Management
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pageContent_Collapse" aria-expanded="true" aria-controls="pageContent_Collapse">
          <i class="fas fa-fw fa-cog"></i>
          <span>Page Content</span>
        </a>
        <div id="pageContent_Collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Page Content :</h6>
            <a class="collapse-item" href="buttons.html">About Us</a>
            <a class="collapse-item" href="cards.html">Contact Us</a>
            <a class="collapse-item" href="cards.html"><i class="fas fa-fw fa-newspaper"></i>&nbsp;News / Announcement</a>
            <a class="collapse-item" href="cards.html">Download</a>
          </div>
        </div>
      </li>
     
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>FAQs Content</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>Email Logs</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>Reference Library</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>Media Library</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Tools
      </div>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-users"></i>
          <span>Code Editor</span></a>
      </li>
      


      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>