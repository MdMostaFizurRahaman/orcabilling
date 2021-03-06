<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Dash Menu -->
                @user
                <li class="sidebar-item {{Request::is('/') ? 'selected': ''}}">
                    <a href="{{route('home')}}" class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Car-Wheel"></i>
                        <span class="hide-menu">Dashboards </span>
                    </a>
                </li>
                <li class="sidebar-item {{Request::is('users/*') ? 'selected': ''}}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-User"></i>
                        <span class="hide-menu">ACL </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level {{Request::is('users/*') ? 'in': ''}}">
                        <li class="sidebar-item {{Request::is('users/*') ? 'active': ''}}">
                            <a href="{{url('users')}}" class="sidebar-link">
                                <i class="mdi mdi-email"></i>
                                <span class="hide-menu"> Users </span>
                            </a>
                        </li>
                        <li class="sidebar-item ">
                            <a href="{{url('roles')}}" class="sidebar-link">
                                <i class="mdi mdi-email-alert"></i>
                                <span class="hide-menu"> Roles</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{url('permissions')}}" class="sidebar-link">
                                <i class="mdi mdi-email-secure"></i>
                                <span class="hide-menu"> Permissions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Sunglasses-Smiley"></i>
                        <span class="hide-menu">Client </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('client.index')}}" class="sidebar-link">
                                <i class="mdi mdi-comment-processing-outline"></i>
                                <span class="hide-menu"> Wholesale Client </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{Request::is('summary/*') ? 'selected' : ''}}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Statistic"></i>
                        <span class="hide-menu">Statistics </span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level {{Request::is('summary/*') ? 'in': ''}}">
                        <li class="sidebar-item">
                            <a href="{{route('cdr.logs')}}" class="sidebar-link">
                                <i class="mdi mdi-weather-fog"></i>
                                <span class="hide-menu"> CDR Logs</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{Request::is('summary/orig-term-calls*') ? 'active': ''}}">
                            <a href="{{route('orig-term-calls.summary.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-toggle-switch"></i>
                                <span class="hide-menu"> Orig-Term Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{Request::is('summary/loss-profit*') ? 'active': ''}}">
                            <a href="{{route('loss-profit.summary.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-tablet"></i>
                                <span class="hide-menu"> Loss-Profit Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{Request::is('summary/success-calls/*') ? 'active': ''}}">
                            <a href="{{route('success-calls.summary.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-image-filter-vintage"></i>
                                <span class="hide-menu"> Success Calls</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{Request::is('summary/failed-calls/*') ? 'active': ''}}">
                            <a href="{{route('failed-calls.summary.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-message-bulleted"></i>
                                <span class="hide-menu"> Failed Calls</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{Request::is('tariffnames/rates/*')  ? 'selected' : ''}}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Wrench"></i>
                        <span class="hide-menu">Tools</span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level {{Request::is('tariffnames/rates/*') ? 'in' : ''}}">
                        <li class="sidebar-item {{Request::is('tariffnames/rates/*') ? 'active' : ''}}">
                            <a href="{{route('tariffname.index')}}" class="sidebar-link">
                                <i class="mdi mdi-layers"></i>
                                <span class="hide-menu"> Rate Generator</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('currencies.index')}}" class="sidebar-link">
                                <i class="mdi mdi-credit-card-scan"></i>
                                <span class="hide-menu">Currency Settings</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('invoice.generate.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-bandcamp"></i>
                                <span class="hide-menu">Generate Invoice</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('invoice.history')}}" class="sidebar-link">
                                <i class="mdi mdi-vector-difference-ba"></i>
                                <span class="hide-menu"> Invoice History</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('bill.simulate.panel')}}" class="sidebar-link">
                                <i class="mdi mdi-flip-to-front"></i>
                                <span class="hide-menu"> Billing Simulation</span>
                            </a>
                        </li>
                    </ul>
                </li>
                </li>
                <li class="sidebar-item {{Request::is('gateways*') || Request::is('company') ? 'selected' : ''}}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)
                        " aria-expanded="false">
                        <i class="icon-Gears"></i>
                        <span class="hide-menu">Configuration</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level {{Request::is('gateways*') || Request::is('company') ? 'in' : ''}}">
                        <li class="sidebar-item">
                            <a href="{{route('gateway.index')}}" class="sidebar-link">
                                <i class="mdi mdi-export"></i>
                                <span class="hide-menu"> VOIP Gateways</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="form-dropzone.html" class="sidebar-link">
                                <i class="mdi mdi-crosshairs-gps"></i>
                                <span class="hide-menu"> Access Device</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('company.settings')}}" class="sidebar-link">
                                <i class="mdi mdi-box-shadow"></i>
                                <span class="hide-menu"> Company Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{Request::is('system') || Request::is('system/*') ? 'selected' : ''}}">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javajavascript:void(0)
                            " aria-expanded="false">
                        <i class="icon-Settings-Window"></i>
                        <span class="hide-menu">System</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level {{Request::is('system') || Request::is('system/*') ? 'in' : ''}}">
                        <li class="sidebar-item {{Request::is('system/access-log*') ? 'active' : ''}}">
                            {{-- <a href="{{route('system.access-log.panel')}}" class="sidebar-link"> --}}
                            <a href="{{route('system.access-log.fetch')}}" class="sidebar-link">
                                <i class="mdi mdi-clock-fast"></i>
                                <span class="hide-menu"> Access Log </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="timeline-horizontal.html" class="sidebar-link">
                                <i class="mdi mdi-clock-end"></i>
                                <span class="hide-menu"> System Status</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="timeline-left.html" class="sidebar-link">
                                <i class="mdi mdi-clock-in"></i>
                                <span class="hide-menu"> Firewall Status</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="timeline-right.html" class="sidebar-link">
                                <i class="mdi mdi-clock-start"></i>
                                <span class="hide-menu"> Media IP Blocker</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="authentication-login2.html" class="sidebar-link">
                                <i class="mdi mdi-account-key"></i>
                                <span class="hide-menu"> Active Registration </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="authentication-register1.html" class="sidebar-link">
                                <i class="mdi mdi-account-plus"></i>
                                <span class="hide-menu"> Debug Tools</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="authentication-register2.html" class="sidebar-link">
                                <i class="mdi mdi-account-plus"></i>
                                <span class="hide-menu"> Web Calls</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @enduser
                <!-- End of User Dash Menu -->

                <!-- Client Dash Menu -->
                @client
                <li class="sidebar-item {{Request::is('/') ? 'selected': ''}}">
                    <a href="{{route('client.home')}}" class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Car-Wheel"></i>
                        <span class="hide-menu">Dashboards </span>
                    </a>
                </li>
                <li class="sidebar-item {{Request::is('client/calls*') ? 'selected': ''}}">
                    <a href="{{route('client.calls-summary.panel')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false">
                        <i class="fas fa-phone"></i>
                        <span class="hide-menu"> Calls Summary</span>
                    </a>
                </li>
                <li class="sidebar-item {{Request::is('client/report*') ? 'selected': ''}}">
                    <a href="{{route('client.report')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false">
                        <i class="icon-Statistic"></i>
                        <span class="hide-menu"> Report</span>
                    </a>
                </li>
                <li class="sidebar-item {{Request::is('client/payments*') ? 'selected': ''}}">
                    <a href="{{route('client.payments.panel')}}" class="sidebar-link waves-effect waves-dark" aria-expanded="false">
                        <i class="far fa-money-bill-alt"></i>
                        <span class="hide-menu"> Payments</span>
                    </a>
                </li>
                @endclient
                <!-- End of Client Dash Menu -->
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
