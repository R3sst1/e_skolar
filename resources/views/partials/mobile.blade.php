        <!-- BEGIN: Mobile Menu -->
        <div class="mobile-menu md:hidden">
            <div class="mobile-menu-bar">
                <a href="{{ route('dashboard') }}" class="flex mr-auto">
                    <img alt="SureScholarShip" class="w-6" src="{{ asset('Images/logo.svg') }}">
                    <span class="ml-2 text-white font-medium">SureScholarShip</span>
                </a>
                <a href="javascript:;" class="mobile-menu-toggler"> 
                    <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> 
                </a>
            </div>
            <div class="scrollable">
                <a href="javascript:;" class="mobile-menu-toggler"> 
                    <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i> 
                </a>
                <ul class="scrollable__content py-2">
                    
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="menu {{ request()->routeIs('dashboard') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="home"></i> </div>
                            <div class="menu__title"> Dashboard </div>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                    <!-- Admin Section -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="settings"></i> </div>
                            <div class="menu__title"> Administration <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="{{ route('applications.applicants') }}" class="menu {{ request()->routeIs('applications.applicants') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="user-plus"></i> </div>
                                    <div class="menu__title"> Applicants </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('scholars') }}" class="menu {{ request()->routeIs('scholars*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="users"></i> </div>
                                    <div class="menu__title"> Scholars </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('disbursements.index') }}" class="menu {{ request()->routeIs('disbursements*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="credit-card"></i> </div>
                                    <div class="menu__title"> Disbursements </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('scholar-performance.index') }}" class="menu {{ request()->routeIs('scholar-performance*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="trending-up"></i> </div>
                                    <div class="menu__title"> Performance </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('renewals.index') }}" class="menu {{ request()->routeIs('renewals*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="refresh-cw"></i> </div>
                                    <div class="menu__title"> Renewals </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('feedback.admin.index') }}" class="menu {{ request()->routeIs('feedback.admin*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="message-square"></i> </div>
                                    <div class="menu__title"> Feedback </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('barangay.data') }}" class="menu {{ request()->routeIs('barangay.data') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="map-pin"></i> </div>
                                    <div class="menu__title"> Barangay Data </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.documents') }}" class="menu {{ request()->routeIs('admin.documents') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="file"></i> </div>
                                    <div class="menu__title"> Documents </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(Auth::user()->isApplicant())
                    <!-- Applicant/Scholar Section -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="graduation-cap"></i> </div>
                            <div class="menu__title"> Scholarship <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="{{ route('applications.create') }}" class="menu {{ request()->routeIs('applications.create') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="file-plus"></i> </div>
                                    <div class="menu__title"> Apply </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('applications.index') }}" class="menu {{ request()->routeIs('applications.*') && !request()->routeIs('applications.create') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="file-text"></i> </div>
                                    <div class="menu__title"> My Applications </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('renewals.create') }}" class="menu {{ request()->routeIs('renewals.create') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="plus-circle"></i> </div>
                                    <div class="menu__title"> Apply Renewal </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('renewals.status') }}" class="menu {{ request()->routeIs('renewals.status') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="refresh-cw"></i> </div>
                                    <div class="menu__title"> Renewal Status </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('disbursements.scholar') }}" class="menu {{ request()->routeIs('disbursements.scholar*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="credit-card"></i> </div>
                                    <div class="menu__title"> My Disbursements </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(Auth::user()->isScholar())
                    <!-- Scholar Section -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="message-square"></i> </div>
                            <div class="menu__title"> Feedback <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="{{ route('feedback.index') }}" class="menu {{ request()->routeIs('feedback*') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="message-square"></i> </div>
                                    <div class="menu__title"> My Feedback </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('feedback.create') }}" class="menu {{ request()->routeIs('feedback.create') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="plus"></i> </div>
                                    <div class="menu__title"> Submit Feedback </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Common Features -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="user"></i> </div>
                            <div class="menu__title"> Profile <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="{{ route('profile.edit') }}" class="menu {{ request()->routeIs('profile.edit') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="user"></i> </div>
                                    <div class="menu__title"> Edit Profile </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}#password" class="menu">
                                    <div class="menu__icon"> <i data-lucide="lock"></i> </div>
                                    <div class="menu__title"> Change Password </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Notifications -->
                    <li>
                        <a href="{{ route('notifications.index') }}" class="menu {{ request()->routeIs('notifications*') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-lucide="bell"></i> </div>
                            <div class="menu__title"> Notifications </div>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <div class="menu__badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</div>
                            @endif
                        </a>
                    </li>

                    <!-- Quick Actions -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="zap"></i> </div>
                            <div class="menu__title"> Quick Actions <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            @if(Auth::user()->isApplicant())
                            <li>
                                <a href="{{ route('applications.create') }}" class="menu">
                                    <div class="menu__icon"> <i data-lucide="file-plus"></i> </div>
                                    <div class="menu__title"> New Application </div>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                            <li>
                                <a href="{{ route('disbursements.create') }}" class="menu">
                                    <div class="menu__icon"> <i data-lucide="credit-card"></i> </div>
                                    <div class="menu__title"> New Disbursement </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('feedback.admin.analytics') }}" class="menu">
                                    <div class="menu__icon"> <i data-lucide="bar-chart"></i> </div>
                                    <div class="menu__title"> Feedback Analytics </div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- System -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-lucide="settings"></i> </div>
                            <div class="menu__title"> System <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="{{ route('action.history') }}" class="menu {{ request()->routeIs('action.history') ? 'menu--active' : '' }}">
                                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                                    <div class="menu__title"> Action History </div>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- Logout -->
                    <li class="menu__devider my-6"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="javascript:;" onclick="document.getElementById('logout-form').submit();" class="menu">
                                <div class="menu__icon"> <i data-lucide="log-out"></i> </div>
                                <div class="menu__title"> Logout </div>
                            </a>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
        <!-- END: Mobile Menu -->
        <!-- END: Mobile Menu -->