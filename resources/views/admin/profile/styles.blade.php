<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --conc-primary: #0f766e;
        --conc-primary-light: #f0fdfa;
        --conc-primary-dark: #0d5f59;
        --conc-accent: #f59e0b;
        --conc-accent-light: #fffbeb;
        --conc-surface: #f8fafc;
        --conc-border: #e2e8f0;
        --conc-text: #1e293b;
        --conc-text-muted: #64748b;
        --conc-success: #10b981;
        --conc-danger: #ef4444;
        --prof-primary: #0f766e;
        --prof-primary-light: #f0fdfa;
        --prof-primary-dark: #0d5f59;
        --prof-accent: #f59e0b;
        --prof-accent-light: #fffbeb;
        --prof-surface: #f8fafc;
        --prof-border: #e2e8f0;
        --prof-text: #1e293b;
        --prof-text-muted: #64748b;
        --prof-success: #10b981;
        --prof-success-light: #ecfdf5;
        --prof-info: #0891b2;
        --prof-info-light: #ecfeff;
        --prof-danger: #ef4444;
        --prof-danger-light: #fef2f2;
        --prof-warning: #f59e0b;
        --prof-warning-light: #fffbeb;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 8px -2px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 25px -4px rgba(0,0,0,0.1), 0 4px 8px -4px rgba(0,0,0,0.06);
    }

    .profile-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--prof-text);
        animation: profFadeIn 0.5s ease-out;
    }

    @keyframes profFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Page Header */
    .profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px 28px;
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        border-radius: var(--radius-lg);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-header h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
        color: white;
    }

    .profile-header p {
        margin: 4px 0 0;
        opacity: 0.8;
        font-size: 0.85rem;
        position: relative;
        z-index: 1;
        color: white;
    }

    .profile-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255,255,255,0.15);
        color: white;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Sidebar Profile Card */
    .profile-sidebar-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .profile-sidebar-banner {
        height: 80px;
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        position: relative;
    }

    .profile-sidebar-banner::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .profile-sidebar-body {
        text-align: center;
        padding: 0 20px 20px;
        margin-top: -48px;
        position: relative;
        z-index: 1;
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 96px;
        height: 96px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: var(--shadow-md);
    }

    .profile-avatar-btn {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--prof-primary);
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .profile-avatar-btn:hover {
        background: var(--prof-primary-dark);
        transform: scale(1.1);
    }

    .profile-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 12px 0 2px;
        color: var(--prof-text);
    }

    .profile-cargo {
        font-size: 0.82rem;
        color: var(--prof-primary);
        font-weight: 600;
        margin-bottom: 6px;
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--prof-primary-light);
        color: var(--prof-primary);
        border: 1px solid rgba(30, 58, 95, 0.15);
        margin-bottom: 14px;
    }

    .profile-mini-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .profile-mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 600;
        background: var(--prof-surface);
        color: var(--prof-text-muted);
        border: 1px solid var(--prof-border);
    }

    .profile-contact-section {
        border-top: 1px solid var(--prof-border);
        padding-top: 14px;
        text-align: left;
    }

    .profile-contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }

    .profile-contact-item + .profile-contact-item {
        border-top: 1px solid var(--prof-border);
    }

    .profile-contact-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .profile-contact-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .profile-contact-value {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--prof-text);
    }

    /* Quick Info Card */
    .quick-info-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .quick-info-header {
        padding: 12px 18px;
        background: var(--prof-surface);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--prof-text-muted);
    }

    .quick-info-header i {
        color: var(--prof-accent);
    }

    .quick-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
    }

    .quick-info-item + .quick-info-item {
        border-top: 1px solid var(--prof-border);
    }

    .quick-info-item .qi-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quick-info-item .qi-left i {
        font-size: 0.85rem;
        color: var(--prof-primary);
    }

    .quick-info-item .qi-label {
        font-size: 0.8rem;
        color: var(--prof-text-muted);
    }

    .quick-info-item .qi-value {
        font-size: 0.82rem;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        color: var(--prof-text);
    }

    /* Main Profile Card */
    .profile-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .profile-card-header {
        border-bottom: 1px solid var(--prof-border);
        background: white;
        padding: 0;
    }

    .profile-card-body {
        padding: 24px;
    }

    /* Navigation Tabs */
    .profile-tabs {
        display: flex;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding: 0 20px;
    }

    .profile-tabs::-webkit-scrollbar { display: none; }

    .profile-tab {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 14px 18px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--prof-text-muted);
        border: none;
        background: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .profile-tab:hover:not(.active) {
        color: var(--prof-primary);
        border-bottom-color: rgba(30, 58, 95, 0.2);
    }

    .profile-tab.active {
        color: var(--prof-primary);
        border-bottom-color: var(--prof-primary);
    }

    .profile-tab i {
        font-size: 1rem;
    }

    /* Data Cards (Personal Tab) */
    .data-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .data-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--prof-border);
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--prof-surface);
    }

    .data-card-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .data-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        color: var(--prof-text);
    }

    .data-card-body {
        padding: 0;
    }

    .data-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
    }

    .data-row + .data-row {
        border-top: 1px solid var(--prof-border);
    }

    .data-row-icon {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .data-row-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .data-row-value {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--prof-text);
    }

    /* Cargo Table */
    .cargo-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .cargo-table thead th {
        background: var(--prof-surface);
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--prof-text-muted);
        border-bottom: 1px solid var(--prof-border);
    }

    .cargo-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--prof-border);
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .cargo-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cargo-table tbody tr:hover {
        background: var(--prof-primary-light);
    }

    .cargo-table tbody tr.row-principal {
        background: rgba(30, 58, 95, 0.02);
    }

    .estado-badge-profile {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .estado-badge-profile.vigente {
        background: var(--prof-success-light);
        color: var(--prof-success);
    }

    .estado-badge-profile.inactivo {
        background: #f1f5f9;
        color: var(--prof-text-muted);
    }

    .principal-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--prof-accent-light);
        color: var(--prof-accent);
        border: 1px solid rgba(217, 119, 6, 0.2);
    }

    /* Estudios Cards */
    .estudio-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
        position: relative;
    }

    .estudio-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .estudio-card.estado-concluido::before { background: var(--prof-success); }
    .estudio-card.estado-en-curso::before { background: var(--prof-warning); }
    .estudio-card.estado-otro::before { background: var(--prof-danger); }

    .estudio-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        transition: all 0.25s ease;
    }

    .estudio-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: var(--prof-surface);
        border-bottom: 1px solid var(--prof-border);
    }

    .estudio-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .estudio-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--prof-text);
    }

    .estudio-profession {
        font-size: 0.75rem;
        color: var(--prof-text-muted);
    }

    .estudio-badges {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .estudio-body {
        padding: 14px 18px;
    }

    .estudio-university {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .estudio-university i {
        font-size: 0.85rem;
        color: var(--prof-text-muted);
    }

    .estudio-university .uni-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--prof-text-muted);
        font-weight: 600;
    }

    .estudio-university .uni-name {
        font-size: 0.82rem;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state-profile {
        padding: 48px 24px;
        text-align: center;
    }

    .empty-state-profile-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 14px;
        background: var(--prof-surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-profile-icon i {
        font-size: 2rem;
        color: #cbd5e1;
    }

    .empty-state-profile h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--prof-text);
        margin-bottom: 4px;
    }

    .empty-state-profile p {
        color: var(--prof-text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Password Tab */
    .password-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .password-card-header {
        background: linear-gradient(135deg, var(--prof-primary) 0%, var(--prof-primary-dark) 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .password-card-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .password-card-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .password-card-header h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .password-card-header p {
        margin: 2px 0 0;
        opacity: 0.75;
        font-size: 0.78rem;
        position: relative;
        z-index: 1;
    }

    .password-card-body {
        padding: 24px;
    }

    .password-input-group {
        display: flex;
        align-items: stretch;
        border-radius: var(--radius-sm);
        border: 1px solid var(--prof-border);
        overflow: hidden;
        background: var(--prof-surface);
        transition: all 0.2s ease;
    }

    .password-input-group:focus-within {
        border-color: var(--prof-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        background: white;
    }

    .password-input-group .input-icon {
        display: flex;
        align-items: center;
        padding: 0 12px;
        color: var(--prof-text-muted);
        background: transparent;
        border: none;
    }

    .password-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 8px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
    }

    .password-input-group .toggle-pw {
        display: flex;
        align-items: center;
        padding: 0 12px;
        background: transparent;
        border: none;
        color: var(--prof-text-muted);
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .password-input-group .toggle-pw:hover {
        color: var(--prof-primary);
    }

    .btn-update-password {
        background: linear-gradient(135deg, var(--prof-primary) 0%, var(--prof-primary-dark) 100%);
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(30, 58, 95, 0.3);
    }

    .btn-update-password:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30, 58, 95, 0.35);
        color: white;
    }

    /* Tips Sidebar */
    .tips-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--prof-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .tips-card-header {
        padding: 14px 20px;
        background: var(--prof-surface);
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--prof-border);
    }

    .tips-card-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(30, 58, 95, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        color: var(--prof-primary);
    }

    .tips-card-header h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.85rem;
    }

    .tips-card-header p {
        margin: 0;
        font-size: 0.72rem;
        color: var(--prof-text-muted);
    }

    .tips-card-body {
        padding: 18px 20px;
        background: linear-gradient(180deg, #fafbff 0%, #fff 100%);
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }

    .tip-item:last-child {
        margin-bottom: 0;
    }

    .tip-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .tip-title {
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--prof-text);
        margin-bottom: 1px;
    }

    .tip-desc {
        font-size: 0.72rem;
        color: var(--prof-text-muted);
        margin: 0;
    }

    /* Progress bar for password strength */
    .pw-strength-bar {
        height: 6px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
    }

    .pw-strength-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.4s ease, background 0.4s ease;
    }

    /* Marketing & Ofertas - Conceptos Color Scheme */
    .mkt-filters-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .mkt-filters-header {
        padding: 14px 22px;
        border-bottom: 1px dashed var(--conc-border);
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--conc-text);
    }

    .mkt-filters-header i {
        color: var(--conc-accent);
    }

    .mkt-filters-body {
        padding: 16px 22px 18px;
    }

    .mkt-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--conc-text-muted);
        margin-bottom: 5px;
        display: block;
    }

    .mkt-select {
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--conc-surface);
        transition: all 0.2s ease;
        width: 100%;
    }

    .mkt-select:focus {
        outline: none;
        border-color: var(--conc-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    .mkt-search-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .mkt-search-group i {
        position: absolute;
        left: 12px;
        color: var(--conc-text-muted);
        font-size: 0.95rem;
        pointer-events: none;
    }

    .mkt-search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-border);
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--conc-surface);
        transition: all 0.2s ease;
    }

    .mkt-search-input:focus {
        outline: none;
        border-color: var(--conc-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    .mkt-btn-filter {
        background: var(--conc-primary);
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .mkt-btn-filter:hover {
        background: var(--conc-primary-dark);
        color: white;
        transform: translateY(-1px);
    }

    .mkt-btn-reset {
        background: white;
        color: var(--conc-text-muted);
        border: 1px solid var(--conc-border);
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .mkt-btn-reset:hover {
        background: var(--conc-surface);
        color: var(--conc-text);
    }

    .mkt-btn-primary {
        background: var(--conc-primary);
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .mkt-btn-primary:hover {
        background: var(--conc-primary-dark);
        color: white;
        transform: translateY(-1px);
    }

    .mkt-btn-outline {
        background: white;
        color: var(--conc-text-muted);
        border: 1px solid var(--conc-border);
        padding: 6px 12px;
        border-radius: var(--radius-sm);
        font-weight: 500;
        font-size: 0.82rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .mkt-btn-outline:hover {
        background: var(--conc-primary-light);
        color: var(--conc-primary);
        border-color: var(--conc-primary);
    }

    .mkt-stat-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: all 0.25s ease;
        height: 100%;
    }

    .mkt-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .mkt-stat-body {
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .mkt-stat-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.4rem;
        line-height: 1.1;
        color: var(--conc-text);
    }

    .mkt-stat-label {
        font-size: 0.75rem;
        color: var(--conc-text-muted);
        margin: 0;
    }

    .mkt-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    /* Chart Cards */
    .mkt-chart-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .mkt-chart-header {
        padding: 16px 20px;
        border-bottom: 1px dashed var(--conc-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mkt-chart-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--conc-text);
    }

    .mkt-chart-title i {
        color: var(--conc-primary);
    }

    .mkt-chart-body {
        padding: 16px 20px;
    }

    .mkt-dropdown-btn {
        background: none;
        border: 1px solid var(--conc-border);
        border-radius: var(--radius-sm);
        padding: 4px 8px;
        color: var(--conc-text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .mkt-dropdown-btn:hover {
        background: var(--conc-surface);
        color: var(--conc-primary);
    }

    /* Table Cards */
    .mkt-table-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--conc-border);
        box-shadow: var(--shadow-sm);
    }

    .mkt-table-card .mkt-table-body {
        padding: 0;
        overflow-x: auto;
    }

    .mkt-table-header {
        padding: 16px 24px;
        border-bottom: 1px dashed var(--conc-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .mkt-table-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--conc-text);
    }

    .mkt-table-title i {
        color: var(--conc-primary);
    }

    .mkt-badge {
        background: var(--conc-primary-light);
        color: var(--conc-primary);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
    }

    .mkt-table-body {
        padding: 0;
    }

    /* Marketing Table */
    .mkt-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .mkt-table thead th {
        background: var(--conc-surface);
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--conc-text-muted);
        border-bottom: 1px solid var(--conc-border);
    }

    .mkt-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--conc-border);
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .mkt-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mkt-table tbody tr:hover {
        background: var(--conc-primary-light);
    }

    /* Ofertas Header */
    .ofertas-header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .ofertas-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--conc-text);
    }

    .ofertas-title i {
        color: var(--conc-primary);
    }

    .ofertas-subtitle {
        font-size: 0.82rem;
        color: var(--conc-text-muted);
        margin: 2px 0 0;
    }

    /* Conc Form Controls */
    .conc-form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--conc-text);
        margin-bottom: 6px;
    }

    .conc-form-control {
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-border);
        padding: 8px 12px;
        font-size: 0.85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--conc-surface);
        transition: all 0.2s ease;
    }

    .conc-form-control:focus {
        border-color: var(--conc-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        background: white;
    }

    /* Modals */
    .modal-conc .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-conc .modal-header {
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        border-bottom: none;
        padding: 18px 24px;
    }

    .modal-conc .modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
    }

    .modal-conc .modal-body {
        padding: 22px 24px;
    }

    .modal-conc .modal-footer {
        border-top: 1px solid var(--conc-border);
        padding: 14px 24px;
        background: var(--conc-surface);
    }

    /* Plan Modal Styles */
    .plan-modal-info-card {
        background: var(--conc-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .plan-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .plan-info-item {
        text-align: center;
    }

    .plan-info-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--conc-text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .plan-info-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--conc-text);
    }

    .plan-selection-section {
        margin-bottom: 20px;
    }

    .plan-section-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--conc-text);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }

    .plan-section-title i {
        color: var(--conc-accent);
    }

    .plan-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .plan-option-card {
        border: 2px solid var(--conc-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .plan-option-card:hover {
        border-color: var(--conc-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .plan-option-card.selected {
        border-color: var(--conc-primary);
        background: var(--conc-primary-light);
    }

    .plan-option-header {
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .plan-option-header h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.88rem;
    }

    .plan-option-price {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .plan-option-body {
        padding: 14px 16px;
    }

    .plan-feature-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        font-size: 0.82rem;
        color: var(--conc-text);
    }

    .plan-feature-item + .plan-feature-item {
        border-top: 1px solid var(--conc-border);
    }

    .plan-feature-item i {
        color: var(--conc-success);
        font-size: 0.9rem;
    }

    .plan-select-btn {
        display: block;
        width: 100%;
        margin-top: 12px;
        padding: 8px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-primary);
        background: white;
        color: var(--conc-primary);
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .plan-select-btn:hover {
        background: var(--conc-primary);
        color: white;
    }

    .plan-generated-section {
        background: var(--conc-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        padding: 20px;
    }

    .plan-link-display {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        border: 1px solid var(--conc-border);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-bottom: 16px;
    }

    .plan-link-text {
        flex: 1;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.82rem;
        color: var(--conc-text);
        word-break: break-all;
    }

    .plan-copy-btn {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-border);
        background: white;
        color: var(--conc-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .plan-copy-btn:hover {
        background: var(--conc-primary);
        color: white;
        border-color: var(--conc-primary);
    }

    .plan-qr-section {
        text-align: center;
    }

    .plan-qr-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--conc-text);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .plan-qr-label i {
        color: var(--conc-accent);
    }

    .plan-qr-container {
        display: inline-block;
        padding: 12px;
        background: white;
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-border);
    }

    .plan-qr-hint {
        font-size: 0.75rem;
        color: var(--conc-text-muted);
        margin-top: 8px;
    }

    /* Enlace Modal Styles */
    .enlace-modal-card {
        background: var(--conc-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        padding: 20px;
        margin-bottom: 16px;
    }

    .enlace-info-section {
        height: 100%;
    }

    .enlace-info-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .enlace-info-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .enlace-info-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--conc-text);
    }

    .enlace-qr-section {
        text-align: center;
        padding: 16px;
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
    }

    .enlace-qr-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin: 0 auto 12px;
    }

    .enlace-qr-image {
        margin-bottom: 8px;
    }

    .enlace-qr-hint {
        font-size: 0.72rem;
        color: var(--conc-text-muted);
        margin: 0;
    }

    .enlace-url-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        overflow: hidden;
    }

    .enlace-url-header {
        padding: 12px 16px;
        background: var(--conc-surface);
        border-bottom: 1px solid var(--conc-border);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--conc-text);
    }

    .enlace-url-header i {
        color: var(--conc-primary);
    }

    .enlace-url-box {
        display: flex;
        align-items: stretch;
        padding: 12px;
    }

    .enlace-url-input {
        flex: 1;
        border: 1px solid var(--conc-border);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        padding: 10px 14px;
        font-size: 0.82rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--conc-surface);
        color: var(--conc-text);
        outline: none;
        transition: all 0.2s ease;
    }

    .enlace-url-input:focus {
        border-color: var(--conc-primary);
        background: white;
    }

    .enlace-url-copy {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        background: var(--conc-primary);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .enlace-url-copy:hover {
        background: var(--conc-primary-dark);
    }

    .btn-enlace-modal {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        border: none;
    }

    .btn-enlace-close {
        background: white;
        color: var(--conc-text-muted);
        border: 1px solid var(--conc-border);
    }

    .btn-enlace-close:hover {
        background: var(--conc-surface);
        color: var(--conc-text);
    }

    .btn-enlace-open {
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.3);
    }

    .btn-enlace-open:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(15, 118, 110, 0.35);
        color: white;
    }

    /* Plan Modal Styles */
    .plan-modal-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .plan-modal-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
    }

    .plan-modal-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .plan-modal-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.75;
        margin-bottom: 2px;
        color: white;
    }

    .plan-modal-value {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        color: white;
    }

    .plan-modal-divider {
        height: 1px;
        background: var(--conc-border);
    }

    .plan-modal-body {
        padding: 14px 20px;
        display: flex;
        gap: 24px;
    }

    .plan-modal-field {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .plan-modal-field-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        background: var(--conc-surface);
        color: var(--conc-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .plan-modal-field-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--conc-text-muted);
        font-weight: 600;
    }

    .plan-modal-field-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--conc-text);
    }

    .plan-select-section {
        margin-bottom: 20px;
    }

    .plan-select-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .plan-select-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background: rgba(245,158,11,0.1);
        color: var(--conc-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .plan-select-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--conc-text);
    }

    .plan-select-subtitle {
        font-size: 0.75rem;
        color: var(--conc-text-muted);
    }

    .plan-loading {
        text-align: center;
        padding: 32px 20px;
        background: var(--conc-surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
    }

    .plan-loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid var(--conc-border);
        border-top-color: var(--conc-primary);
        border-radius: 50%;
        animation: spinAnim 0.8s linear infinite;
        margin: 0 auto 12px;
    }

    @keyframes spinAnim {
        to { transform: rotate(360deg); }
    }

    .plan-loading p {
        color: var(--conc-text-muted);
        font-size: 0.82rem;
        margin: 0;
    }

    /* Plan Cards */
    .plan-card {
        border: 2px solid var(--conc-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        cursor: pointer;
        transition: all 0.25s ease;
        height: 100%;
    }

    .plan-card:hover {
        border-color: var(--conc-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .plan-card.selected {
        border-color: var(--conc-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }

    .plan-card-header {
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .plan-card-header h6 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        font-size: 0.9rem;
        color: white;
    }

    .plan-card-price {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.15rem;
        color: white;
    }

    .plan-card-body {
        padding: 14px 18px;
    }

    .plan-card-feature {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        font-size: 0.82rem;
        color: var(--conc-text);
    }

    .plan-card-feature + .plan-card-feature {
        border-top: 1px solid var(--conc-border);
    }

    .plan-card-feature i {
        color: var(--conc-success);
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .plan-card-btn {
        display: block;
        width: calc(100% - 36px);
        margin: 0 18px 18px;
        padding: 8px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--conc-primary);
        background: white;
        color: var(--conc-primary);
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .plan-card-btn:hover {
        background: var(--conc-primary);
        color: white;
    }

    /* Plan Result Section */
    .plan-result-section {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        overflow: hidden;
    }

    .plan-result-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #0f766e 0%, #0d5f59 100%);
        color: white;
    }

    .plan-result-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .plan-result-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .plan-result-subtitle {
        font-size: 0.75rem;
        opacity: 0.75;
    }

    .plan-result-link {
        display: flex;
        align-items: stretch;
        padding: 16px;
        gap: 0;
        border-bottom: 1px solid var(--conc-border);
    }

    .plan-result-url {
        flex: 1;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.82rem;
        color: var(--conc-text);
        word-break: break-all;
        padding: 10px 14px;
        background: var(--conc-surface);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        border: 1px solid var(--conc-border);
        border-right: none;
    }

    .plan-result-copy {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        background: var(--conc-primary);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .plan-result-copy:hover {
        background: var(--conc-primary-dark);
    }

    .plan-result-qr {
        text-align: center;
        padding: 20px;
    }

    .plan-qr-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--conc-text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .plan-qr-label i {
        color: var(--conc-accent);
    }

    .plan-qr-box {
        display: inline-block;
        padding: 14px;
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--conc-border);
        box-shadow: var(--shadow-sm);
    }

    .plan-qr-hint {
        font-size: 0.75rem;
        color: var(--conc-text-muted);
        margin-top: 10px;
    }

    /* Ofertas table action buttons */
    .oferta-action-btn {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .oferta-action-btn.link-btn {
        background: var(--conc-primary-light);
        color: var(--conc-primary);
        border-color: rgba(15, 118, 110, 0.2);
    }

    .oferta-action-btn.link-btn:hover {
        background: var(--conc-primary);
        color: white;
        transform: translateY(-1px);
    }

    .oferta-action-btn.plan-btn {
        background: var(--conc-accent-light);
        color: var(--conc-accent);
        border-color: rgba(245, 158, 11, 0.2);
    }

    .oferta-action-btn.plan-btn:hover {
        background: var(--conc-accent);
        color: white;
        transform: translateY(-1px);
    }

    /* Modals */
    .modal-profile .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-profile .modal-header {
        background: linear-gradient(135deg, var(--prof-primary) 0%, var(--prof-primary-dark) 100%);
        color: white;
        border-bottom: none;
        padding: 18px 24px;
    }

    .modal-profile .modal-header h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-profile .modal-body {
        padding: 22px 24px;
    }

    .modal-profile .modal-footer {
        border-top: 1px solid var(--prof-border);
        padding: 14px 24px;
        background: var(--prof-surface);
    }

    /* Toast */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 999999 !important;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .profile-card-body {
            padding: 16px;
        }
        .profile-tabs {
            padding: 0 12px;
        }
        .profile-tab {
            padding: 12px 14px;
            font-size: 0.78rem;
        }
    }
</style>
