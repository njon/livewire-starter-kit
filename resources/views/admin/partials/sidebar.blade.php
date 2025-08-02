<div class="sidebar">
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="/admin/">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Catalog Accordion -->
            <li class="nav-item accordion-item">
                <h2 class="accordion-header">
                    <button class="nav-link accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#catalogCollapse">
                        <span>Catalog</span>
                        <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd"
                                d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="catalogCollapse" class="accordion-collapse collapse show">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}"
                                    href="/admin/products">
                                    <i class="bi bi-tag"></i>
                                    <span>Services</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/stores*') ? 'active' : '' }}"
                                    href="/admin/stores">
                                    <i class="bi bi-shop"></i>
                                    <span>Stores</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <!-- Sales Accordion -->
            <li class="nav-item accordion-item">
                <h2 class="accordion-header">
                    <button class="nav-link accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#salesCollapse">
                        <span>Sales</span>
                        <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd"
                                d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="salesCollapse" class="accordion-collapse collapse show">
                    <div class="accordion-body p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}"
                                    href="/admin/orders">
                                    <i class="bi bi-cart3"></i>
                                    <span>Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-people"></i>
                                    <span>Staff</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="nav-item accordion-item">
            <h2 class="accordion-header">
                <button class="nav-link accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#helpCollapse">
                    <span>Help</span>
                    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                            d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </h2>
            <div id="helpCollapse" class="accordion-collapse collapse show">
                <div class="accordion-body p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item mt-2 pt-2">
                            <a class="nav-link {{ Request::is('admin/support*') ? 'active' : '' }}"
                                href="/admin/support">
                                <i class="bi bi-question-circle"></i>
                                <span>Support</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/docs*') ? 'active' : '' }}" href="/admin/docs">
                                <i class="bi bi-journal-text"></i>
                                <span>Docs</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        </ul>

    </div>
</div>