<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <ul>
    
        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}" class="side-menu {{ request()->routeIs('dashboard') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">Dashboard</div>
            </a>
        </li>

        <li class="side-nav__devider my-4"></li>
       
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
        <li>
            <a href="{{ route('accounts.index') }}" class="side-menu {{ request()->routeIs('accounts.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="user-check"></i>
                </div>
                <div class="side-menu__title">Account Management</div>
            </a>
        </li>
        @endif
        @if(Auth::user()->isSuperAdmin())
        <!-- Super Admin Section -->
        <li>
            <a href="{{ route('super-admin.reports') }}" class="side-menu {{ request()->routeIs('super-admin.reports*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">Generate Reports</div>
            </a>
        </li>
        <li>
            <a href="{{ route('system-settings.index') }}" class="side-menu {{ request()->routeIs('system-settings*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="settings"></i>
                </div>
                <div class="side-menu__title">System Settings</div>
            </a>
        </li>
        <li>
            <a href="{{ route('institutions.index') }}" class="side-menu {{ request()->routeIs('institutions*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="building"></i>
                </div>
                <div class="side-menu__title">Institution Management</div>
            </a>
        </li>

        <li class="side-nav__devider my-4"></li>
        @endif

        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
        <!-- Admin Section -->
        <li>
            <a href="{{ route('scholarship.management') }}" class="side-menu {{ request()->routeIs('scholarship.management') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="award"></i>
                </div>
                <div class="side-menu__title">Scholarship Management</div>
            </a>
        </li>
        <li>
            <a href="{{ route('applications.applicants') }}" class="side-menu {{ request()->routeIs('applications.applicants') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="user-plus"></i>
                </div>
                <div class="side-menu__title">Applicants</div>
            </a>
        </li>
        <li>
            <a href="{{ route('scholars') }}" class="side-menu {{ request()->routeIs('scholars') || request()->routeIs('scholars.drop') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">Scholars</div>
            </a>
        </li>
        <li>
            <a href="{{ route('disbursements.index') }}" class="side-menu {{ request()->routeIs('disbursements*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="credit-card"></i>
                </div>
                <div class="side-menu__title">Disbursements</div>
            </a>
        </li>
        <li>
            <a href="{{ route('budget-requests.index') }}" class="side-menu {{ request()->routeIs('budget-requests*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="dollar-sign"></i>
                </div>
                <div class="side-menu__title">Budget Requests</div>
            </a>
        </li>
        <li>
            <a href="{{ route('scholar-release.index') }}" class="side-menu {{ request()->routeIs('scholar-release*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="send"></i>
                </div>
                <div class="side-menu__title">Scholar Release</div>
            </a>
        </li>
        <li>
            <a href="{{ route('scholar-performance.index') }}" class="side-menu {{ request()->routeIs('scholar-performance*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="trending-up"></i>
                </div>
                <div class="side-menu__title">Scholar Performance</div>
            </a>
        </li>
        <li>
            <a href="{{ route('renewals.index') }}" class="side-menu {{ request()->routeIs('renewals*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="refresh-cw"></i>
                </div>
                <div class="side-menu__title">Renewal Management</div>
            </a>
        </li>
        <!-- <li>
            <a href="{{ route('barangay.data') }}" class="side-menu {{ request()->routeIs('barangay.data') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="map-pin"></i>
                </div>
                <div class="side-menu__title">Barangay Data</div>
            </a>
        </li> -->
        <li>
            <a href="{{ route('residence-data.index') }}" class="side-menu {{ request()->routeIs('residence-data.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">Residence Data</div>
            </a>
        </li>
       
        <li>
            <a href="{{ route('admin.documents') }}" class="side-menu {{ request()->routeIs('admin.documents') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file"></i>
                </div>
                <div class="side-menu__title">Uploaded Documents</div>
            </a>
        </li>
        <li>
            <a href="{{ route('feedback.admin.index') }}" class="side-menu {{ request()->routeIs('feedback.admin*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="message-square"></i>
                </div>
                <div class="side-menu__title">Feedback Management</div>
            </a>
        </li>
        @endif

        @if(Auth::user()->isApplicant())
        <!-- Applicant Section -->
        <li>
            <a href="{{ route('applications.create') }}" class="side-menu {{ request()->routeIs('applications.create') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-plus"></i>
                </div>
                <div class="side-menu__title">Apply for Scholarship</div>
            </a>
        </li>
        <li>
            <a href="{{ route('applications.index') }}" class="side-menu {{ request()->routeIs('applications.*') && !request()->routeIs('applications.create') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">My Applications</div>
            </a>
        </li>
        <li>
            <a href="{{ route('renewals.create') }}" class="side-menu {{ request()->routeIs('renewals.create') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="plus-circle"></i>
                </div>
                <div class="side-menu__title">Apply for Renewal</div>
            </a>
        </li>
        <li>
            <a href="{{ route('renewals.status') }}" class="side-menu {{ request()->routeIs('renewals.status') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="refresh-cw"></i>
                </div>
                <div class="side-menu__title">Renewal Status</div>
            </a>
        </li>
        <li>
            <a href="{{ route('disbursements.scholar') }}" class="side-menu {{ request()->routeIs('disbursements.scholar*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="credit-card"></i>
                </div>
                <div class="side-menu__title">My Disbursements</div>
            </a>
        </li>
        <li>
            <a href="{{ route('notifications.index') }}" class="side-menu {{ request()->routeIs('notifications*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="bell"></i>
                </div>
                <div class="side-menu__title">Notifications</div>
                @if(Auth::user()->unreadNotifications->count() > 0)
                <div class="side-menu__badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</div>
                @endif
            </a>
        </li>
        @endif

        @if(Auth::user()->isScholar())
        <!-- Scholar Section -->
        <li>
            <a href="{{ route('feedback.index') }}" class="side-menu {{ request()->routeIs('feedback*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="message-square"></i>
                </div>
                <div class="side-menu__title">My Feedback</div>
            </a>
        </li>
        @endif

        <!-- Common Section -->
        <li class="side-nav__devider my-4"></li>

        <!-- <li>
            <a href="{{ route('action.history') }}" class="side-menu {{ request()->routeIs('action.history') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="activity"></i>
                </div>
                <div class="side-menu__title">Action History</div>
            </a>
        </li> -->
    </ul>
</nav>
<!-- END: Side Menu -->