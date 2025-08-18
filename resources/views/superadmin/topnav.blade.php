  <body class="nav-fixed">
      <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
          <!-- Sidenav Toggle Button-->
          <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
          <!-- Navbar Brand-->
          <!-- * * Tip * * You can use text or an image for your navbar brand.-->
          <!-- * * * * * * When using an image, we recommend the SVG format.-->
          <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
          <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ route('superadmin_dashboard') }}">REALS - DBPSC</a>
          <!-- Navbar Items-->
          <ul class="navbar-nav align-items-center ms-auto">
              <!-- Navbar Search Dropdown-->
              <!-- Alerts Dropdown-->
              <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                  <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="bell"></i></a>
                  <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                      <h6 class="dropdown-header dropdown-notifications-header">
                          <i class="me-2" data-feather="bell"></i>
                          Alerts Center
                      </h6>
                      <!-- Example Alert 1-->
                      <a class="dropdown-item dropdown-notifications-item" href="#!">
                          <div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
                          <div class="dropdown-notifications-item-content">
                              <div class="dropdown-notifications-item-content-details">December 29, 2021</div>
                              <div class="dropdown-notifications-item-content-text">This is an alert message. It's nothing serious, but it requires your attention.</div>
                          </div>
                      </a>
                      <a class="dropdown-item dropdown-notifications-footer" href="#!">View All Alerts</a>
                  </div>
              </li>
              <!-- Messages Dropdown-->
              <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                  <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="mail"></i></a>
                  <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                      <h6 class="dropdown-header dropdown-notifications-header">
                          <i class="me-2" data-feather="mail"></i>
                          Message Center
                      </h6>
                      <!-- Example Message 1  -->
                      <a class="dropdown-item dropdown-notifications-item" href="#!">
                          <img class="dropdown-notifications-item-img" src="{{ asset('assets/assets/img/illustrations/profiles/profile-2.png') }}" />
                          <div class="dropdown-notifications-item-content">
                              <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                              <div class="dropdown-notifications-item-content-details">Thomas Wilcox · 58m</div>
                          </div>
                      </a>
                      <!-- Footer Link-->
                      <a class="dropdown-item dropdown-notifications-footer" href="#!">Read All Messages</a>
                  </div>
              </li>
              <!-- User Dropdown-->
              <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                  <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img class="img-fluid" src="{{ Auth::guard('superadmin')->user()->photo ? asset('assets/users/superadmin/' . Auth::guard('superadmin')->user()->photo) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" />
                  </a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow dropdown-menu-animation" aria-labelledby="navbarDropdownUserImage">  <h6 class="dropdown-header d-flex align-items-center">
                          <img class="dropdown-user-img" src="{{ Auth::guard('superadmin')->user()->photo ? asset('assets/users/superadmin/' . Auth::guard('superadmin')->user()->photo) : asset('assets/assets/img/illustrations/profiles/profile-1.png') }}" />
                          <div class="dropdown-user-details">
                              <div class="dropdown-user-details-name">{{ Auth::guard('superadmin')->user()->firstname }} {{ Auth::guard('superadmin')->user()->lastname }}</div>
                              <div class="dropdown-user-details-email">{{ Auth::guard('superadmin')->user()->email }}</div>
                          </div>
                      </h6>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route('superadmin_editsuperadmin', Auth::guard('superadmin')->user()->id) }}">
                          <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                          Account
                      </a>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                          <div class="dropdown-item-icon"><i data-feather="lock"></i></div>
                          Change Password
                      </a>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                          <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                          Logout
                      </a>
                  </div>
              </li>
          </ul>
      </nav>

      <!-- Change Password Modal (moved outside nav for proper display) -->
      <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <form action="{{ route('superadmin_changepassword', Auth::guard('superadmin')->user()->id) }}" method="POST">
                      @csrf
                      <div class="modal-header">
                          <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <div class="form-floating mb-3">
                              <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Old Password" required>
                              <label for="oldpassword">Old Password</label>
                          </div>
                          <div class="form-floating mb-3">
                              <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" required>
                              <label for="newpassword">New Password</label>
                          </div>
                          <div class="form-floating mb-3">
                              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                              <label for="confirmpassword">Confirm New Password</label>
                          </div>
                      </div>
                      <div class="modal-footer d-flex justify-content-end">
                          <div class="btn-group" role="group" aria-label="Password Actions">
                              <button type="button" class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-dismiss="modal">
                                  <i data-feather="x" class="me-1"></i>
                                  Cancel
                              </button>
                              <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                  <i data-feather="key" class="me-1"></i>
                                  Change Password
                              </button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>

      <!-- Logout Confirmation Modal (move outside nav and dropdown) -->
      <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      Are you sure you want to logout?
                  </div>
                  <div class="modal-footer d-flex justify-content-end">
                      <div class="btn-group" role="group" aria-label="Logout Actions">
                          <button type="button" class="btn btn-danger btn-sm d-inline-flex align-items-center" data-bs-dismiss="modal">
                              <i data-feather="x" class="me-1"></i>
                              Cancel
                          </button>
                          <a href="{{ route('superadmin_logout') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                              <i data-feather="log-out" class="me-1"></i>
                              Logout
                          </a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      </div>
      </li>
      </ul>
      </nav>

      <!-- Logout Confirmation Modal (move outside nav and dropdown) -->
      <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      Are you sure you want to logout?
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                      <a href="{{ route('superadmin_logout') }}" class="btn btn-primary">Logout</a>
                  </div>
              </div>
          </div>
      </div>
      <!-- Password Change Feedback Modal -->
      @if(session('success2') || session('error') || $errors->any())
      <div class="modal fade" id="passwordFeedbackModal" tabindex="-1" aria-labelledby="passwordFeedbackModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="passwordFeedbackModalLabel">
                          @if(session('success2'))
                          Success
                          @elseif(session('error') || $errors->any())
                          Error
                          @endif
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      @if(session('success2'))
                      <div class="alert alert-success mb-0">{{ session('success2') }}</div>
                      @endif
                      @if(session('error'))
                      <div class="alert alert-danger mb-0">{{ session('error') }}</div>
                      @endif
                      @if($errors->any())
                      <div class="alert alert-danger mb-0">
                          <ul class="mb-0">
                              @foreach($errors->all() as $error)
                              <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              var feedbackModal = new bootstrap.Modal(document.getElementById('passwordFeedbackModal'));
              feedbackModal.show();
          });
      </script>
      @endif