<div class="header">
            <div class="container-fluid d-flex flex-row align-items-center justify-content-between py-2 px-3" style="gap: 10px;">

                <a href="../Webpages/homepage.php" class="text-decoration-none d-flex align-items-center">
                    <img src="../../icons/logo-icon.png" alt="Cerina's Sari2Store Logo" style="height: 40px; width: auto;">
                </a>

                <div class="d-flex flex-grow-1 me-3" style="max-width: 700px;">

                    <div class="d-flex flex-row flex-grow-1">
                        <input type="text" class="form-control" id="search_input" placeholder="Search">
                        <button class="btn btn-light search-button" id="search_button" type="button"
                            aria-label="Search">
                            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg"
                                viewBox="-81.92 -81.92 1187.84 1187.84">
                                <path d="m795.904 750.72 124.992 124.928a32 32 0 0 1-45.248 
                                        45.248L750.656 795.904a416 416 0 1 1 45.248-45.248z
                                        M480 832a352 352 0 1 0 0-704 352 352 0 0 0 0 704z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <button class="btn nav-icon" onclick="window.location.href='../Cart/cart.php'" aria-label="Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M6.29977 5H21L19 12H7.37671M20 16H8L6 3H3
                                    M9 20C9 20.5523 8.55228 21 8 21
                                    C7.44772 21 7 20.5523 7 20
                                    C7 19.4477 7.44772 19 8 19
                                    C8.55228 19 9 19.4477 9 20
                                    Z
                                    M20 20C20 20.5523 19.5523 21 19 21
                                    C18.4477 21 18 20.5523 18 20
                                    C18 19.4477 18.4477 19 19 19
                                    C19.5523 19 20 19.4477 20 20Z" />
                        </svg>
                    </button>

                    <button class="btn nav-icon" onclick="window.location.href='../Orders/order.php'"
                        aria-label="Reservation">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M16 8H17.1597C18.1999 8 19.0664 8.79732
                                    19.1528 9.83391L19.8195 17.8339
                                    C19.9167 18.9999 18.9965 20 17.8264 20
                                    H6.1736C5.00352 20 4.08334 18.9999
                                    4.18051 17.8339L4.84718 9.83391
                                    C4.93356 8.79732 5.80009 8 6.84027 8H8
                                    M16 8H8M16 8L16 7C16 5.93913 15.5786 4.92172
                                    14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3
                                    C10.9391 3 9.92172 3.42143 9.17157 4.17157
                                    C8.42143 4.92172 8 5.93913 8 7L8 8M16 8L16 12M8 8L8 12" />
                        </svg>
                    </button>

                    <div class="dropdown">
                        <button class="btn nav-icon d-flex flex-row position-relative" aria-label="Notifications"
                            type="button" id="notifications" data-bs-toggle="modal" aria-expanded="false"
                            data-bs-target="#notificationModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12.0196 2.91016C8.7096 2.91016 6.0196 5.60016 6.0196 8.91016
                                    V11.8002C6.0196 12.4102 5.7596 13.3402 5.4496 13.8602
                                    L4.2996 15.7702C3.5896 16.9502 4.0796 18.2602 5.3796 18.7002
                                    C9.6896 20.1402 14.3396 20.1402 18.6496 18.7002
                                    C19.8596 18.3002 20.3896 16.8702 19.7296 15.7702
                                    L18.5796 13.8602C18.2796 13.3402 18.0196 12.4102 18.0196 11.8002
                                    V8.91016C18.0196 5.61016 15.3196 2.91016 12.0196 2.91016Z" />

                                <path d="M13.8699 3.19994C13.5599 3.10994 13.2399 3.03994 12.9099 2.99994
                                    C11.9499 2.87994 11.0299 2.94994 10.1699 3.19994
                                    C10.4599 2.45994 11.1799 1.93994 12.0199 1.93994
                                    C12.8599 1.93994 13.5799 2.45994 13.8699 3.19994Z" />

                                <path opacity="0.4" d="M15.0195 19.0601C15.0195 20.7101 13.6695 22.0601
                                    12.0195 22.0601C11.1995 22.0601 10.4395 21.7201
                                    9.89953 21.1801C9.35953 20.6401 9.01953 19.8801 9.01953 19.0601" />
                            </svg>
                            <div id="notification_count"></div>
                        </button>
                    </div>

                    <div class="dropdown ms-auto">
                        <button id="profile_dropdown" class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">Profile</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- <?php session_start(); ?>
                            <?php if (isset($_SESSION['staff_type']) && $_SESSION['staff_type'] == 'staff'): ?>
                                <a class="dropdown-item" id="adminLink" href="../Admin/staffPage.php">Staff</a>
                            <?php elseif (isset($_SESSION['staff_type']) && $_SESSION['staff_type'] == 'inventory'): ?>
                                <a class="dropdown-item" id="adminLink" href="../Admin/inventoryPage.php">Inventory</a>
                            <?php endif; ?> -->
                            <a class="dropdown-item" id="authLink" onclick="signoutClick(event)">Logout</a>
                        </ul>
                    </div>
                </div>
            </div>

        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body" style=" overflow-y: auto;">
                        <div id="notifications_popup"></div>
                        <div class="p-4 d-flex justify-content-center align-items-center gap-4">
                            <button class="navButton" type="button" id="notif_prev_button">Previous</button>
                            <span>|</span>
                            <div id="notif_page_number" class="paragraphs">Page # out of #</div>
                            <span>|</span>
                            <button class="navButton" type="button" id="notif_next_button">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>