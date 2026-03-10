<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo rtrim(BASE_URL, '/'); ?>">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/main.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232563eb'><path d='M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'/></svg>">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light: #f9fafb;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
            color: #065f46;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fef2f2 100%);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            color: #78350f;
            border: 1px solid #fde68a;
        }

        .alert-info {
            background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
            color: #0c4a6e;
            border: 1px solid #bae6fd;
        }

        /* Close button for alerts */
        .alert-close {
            float: right;
            cursor: pointer;
            font-weight: bold;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Utility classes */
        .mt-3 {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <?php
    // Global flash messages (success / error / warning / info)
    require_once __DIR__ . '/../../core/Session.php';
    if (Session::hasFlash('success')): ?>
        <div class="alert alert-success" role="alert" style="margin: 0;">
            <span class="alert-close" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>✓ Success!</strong> <?php echo htmlspecialchars(Session::getFlash('success')); ?>
        </div>
    <?php endif; ?>
    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert" style="margin: 0;">
            <span class="alert-close" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>✗ Error!</strong> <?php echo htmlspecialchars(Session::getFlash('error')); ?>
        </div>
    <?php endif; ?>
    <?php if (Session::hasFlash('warning')): ?>
        <div class="alert alert-warning" role="alert" style="margin: 0;">
            <span class="alert-close" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>⚠ Warning!</strong> <?php echo htmlspecialchars(Session::getFlash('warning')); ?>
        </div>
    <?php endif; ?>
    <?php if (Session::hasFlash('info')): ?>
        <div class="alert alert-info" role="alert" style="margin: 0;">
            <span class="alert-close" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>ℹ Info:</strong> <?php echo htmlspecialchars(Session::getFlash('info')); ?>
        </div>
    <?php endif; ?>
