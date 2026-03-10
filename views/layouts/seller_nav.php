<nav style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 1rem 0; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid #e2e8f0;">
    <style>
        .nav-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-link:hover {
            color: #2563eb;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2563eb, #1e40af);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-user {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding-left: 1.5rem;
            border-left: 1px solid #e2e8f0;
        }

        .nav-username {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .nav-logout {
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }

        .nav-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-wrap: wrap;
            }

            .nav-links {
                gap: 1rem;
                font-size: 0.85rem;
            }

            .nav-user {
                border-left: none;
                border-top: 1px solid #e2e8f0;
                padding-left: 0;
                padding-top: 1rem;
                margin-top: 1rem;
                width: 100%;
                order: 3;
            }
        }
    </style>

    <div class="container" style="display: flex; justify-content: space-between; align-items: center; gap: 2rem;">
        <a href="<?php echo url('/seller/dashboard'); ?>" class="nav-brand">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            Seller Hub
        </a>
        
        <div class="nav-links">
            <a href="<?php echo url('/seller/dashboard'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Dashboard
            </a>
            <a href="<?php echo url('/seller/products'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <path d="M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                </svg>
                Products
            </a>
            <a href="<?php echo url('/seller/inventory'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Inventory
            </a>
            <a href="<?php echo url('/seller/pricing'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Pricing
            </a>
            <a href="<?php echo url('/seller/orders'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                </svg>
                Orders
            </a>
            <a href="<?php echo url('/seller/analytics'); ?>" class="nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 0.25rem;">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                Analytics
            </a>
            
            <?php if (\Session::isLoggedIn()): ?>
                <div class="nav-user">
                    <span class="nav-username">
                        👤 <?php echo htmlspecialchars(\Session::getUsername()); ?>
                    </span>
                    <a href="<?php echo url('/auth/logout'); ?>" class="nav-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
