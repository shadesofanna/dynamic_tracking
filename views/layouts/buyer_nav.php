<nav style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 1rem 0; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid #e2e8f0;">
    <style>
        .buyer-nav-brand {
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

        .buyer-nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .buyer-nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .buyer-nav-link:hover {
            color: #2563eb;
        }

        .buyer-nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2563eb, #1e40af);
            transition: width 0.3s ease;
        }

        .buyer-nav-link:hover::after {
            width: 100%;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -12px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: none;
            font-size: 12px;
            text-align: center;
            line-height: 22px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            animation: badgePop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes badgePop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .buyer-nav-user {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding-left: 1.5rem;
            border-left: 1px solid #e2e8f0;
        }

        .buyer-nav-welcome {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .buyer-nav-logout {
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
        }

        .buyer-nav-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
        }

        .buyer-nav-login {
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

        .buyer-nav-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 768px) {
            .buyer-nav-links {
                gap: 1rem;
                font-size: 0.85rem;
            }

            .buyer-nav-user {
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
        <a href="<?php echo url('/buyer/shop'); ?>" class="buyer-nav-brand">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <?php echo APP_NAME; ?>
        </a>
        
        <div class="buyer-nav-links">
            <a href="<?php echo url('/buyer/shop'); ?>" class="buyer-nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                </svg>
                Shop
            </a>
            <a href="<?php echo url('/buyer/cart'); ?>" class="buyer-nav-link" style="position: relative;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Cart
                <span id="cart-count" class="cart-badge">0</span>
            </a>
            <a href="<?php echo url('/buyer/orders'); ?>" class="buyer-nav-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                </svg>
                Orders
            </a>
            
            <?php if (\Session::isLoggedIn()): ?>
                <div class="buyer-nav-user">
                    <span class="buyer-nav-welcome">
                        👤 Welcome, <?php echo htmlspecialchars(\Session::getUsername()); ?>
                    </span>
                    <a href="<?php echo url('/auth/logout'); ?>" class="buyer-nav-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            <?php else: ?>
                <a href="<?php echo url('/login'); ?>" class="buyer-nav-login">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
