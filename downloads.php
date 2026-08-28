<?php
$pageTitle = 'Downloads – Kilele Tech';
include 'includes/header.php';

require_once 'includes/db.php';

// Fetch real download counts from the database
$counts = [];
try {
    $stmt = $pdo->query("SELECT * FROM download_counts");
    $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    // If table doesn't exist, counts will just remain 0
}
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="/index.php">Home</a> / Downloads</div>
        <h1 class="page-hero__title">Downloads</h1>
        <p class="page-hero__subtitle">
            Company profile, service brochures and documentation you may need before or during a project.
        </p>
    </div>
</section>

<section class="content-section" style="padding: 50px 0;">
    <div class="container">
        
        <!-- Toolbar -->
        <div class="downloads-toolbar" style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; align-items: center; justify-content: space-between; background: #f8fafb; padding: 16px 20px; border-radius: 12px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="font-size: 14px; color: var(--kilele-text-light);">Show</span>
                <select id="perPageSelect" onchange="filterDownloads()" style="padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; outline: none; cursor: pointer;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
                <span style="font-size: 14px; color: var(--kilele-text-light);">per page</span>
                <span style="font-size: 14px; color: var(--kilele-text-light); margin-left: 10px;">
                    <span id="resultsCount">0</span> results found
                </span>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <select id="categoryFilter" onchange="filterDownloads()" style="padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; outline: none; cursor: pointer;">
                    <option value="all">All Categories</option>
                    <option value="Company">Company Documents</option>
                    <option value="Guides">Guides & Brochures</option>
                    <option value="Policies">Policies</option>
                    <option value="Reports">Reports</option>
                    <option value="Technical">Technical Documents</option>
                </select>
                <select id="typeFilter" onchange="filterDownloads()" style="padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; outline: none; cursor: pointer;">
                    <option value="all">All Types</option>
                    <option value="PDF">PDF</option>
                    <option value="DOC">DOC/DOCX</option>
                    <option value="XLS">XLS/XLSX</option>
                    <option value="ZIP">ZIP</option>
                </select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 13px;"></i>
                    <input type="text" id="searchDownloads" placeholder="Search..." style="padding: 8px 16px 8px 36px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; transition: 0.3s; background: #fff; min-width: 180px;" onkeyup="filterDownloads()">
                </div>
            </div>
        </div>

        <!-- Downloads Table -->
        <div class="downloads-table-wrapper" style="overflow-x: auto; background: #fff; border-radius: 12px; border: 1px solid #e9ecef;">
            <table class="downloads-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafb; border-bottom: 2px solid #e9ecef;">
                        <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: var(--kilele-navy); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Title</th>
                        <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: var(--kilele-navy); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Category</th>
                        <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: var(--kilele-navy); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">File Info</th>
                        <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: var(--kilele-navy); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Upload Date</th>
                        <th style="padding: 14px 18px; text-align: center; font-weight: 700; color: var(--kilele-navy); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                    </tr>
                </thead>
                <tbody id="downloadsBody">
                    <?php
                    // ALL COUNTS START AT 0
                    $downloads = [
                        [
                            'title' => 'Kilele Tech Company Profile',
                            'category' => 'Company',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2026-08-10',
                            'size' => '0.42',
                            'file' => 'company-profile.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Certificate of Incorporation',
                            'category' => 'Company',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2025-08-24',
                            'size' => '0.12',
                            'file' => 'certificate-of-incorporation.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Service Catalogue & Pricing Guide',
                            'category' => 'Guides',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2026-08-01',
                            'size' => '0.31',
                            'file' => 'service-catalogue.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'ICT Consulting Engagement Guide',
                            'category' => 'Guides',
                            'type' => 'PDF',
                            'icon' => 'fa-file-lines',
                            'color' => '#e74c3c',
                            'date' => '2026-07-15',
                            'size' => '0.18',
                            'file' => 'ict-consulting-guide.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Security Best Practices Guide',
                            'category' => 'Guides',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2026-07-20',
                            'size' => '0.85',
                            'file' => 'security-best-practices.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Standard Service Level Agreement (SLA)',
                            'category' => 'Policies',
                            'type' => 'PDF',
                            'icon' => 'fa-file-contract',
                            'color' => '#e74c3c',
                            'date' => '2026-07-01',
                            'size' => '0.25',
                            'file' => 'standard-sla.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Data Privacy & Security Policy',
                            'category' => 'Policies',
                            'type' => 'PDF',
                            'icon' => 'fa-shield-halved',
                            'color' => '#e74c3c',
                            'date' => '2026-06-15',
                            'size' => '0.15',
                            'file' => 'data-privacy-policy.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Acceptable Use Policy (AUP)',
                            'category' => 'Policies',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2026-06-10',
                            'size' => '0.18',
                            'file' => 'acceptable-use-policy.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Security Monitoring Whitepaper',
                            'category' => 'Reports',
                            'type' => 'PDF',
                            'icon' => 'fa-file-shield',
                            'color' => '#e74c3c',
                            'date' => '2026-07-25',
                            'size' => '1.1',
                            'file' => 'security-monitoring-whitepaper.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Annual Report 2025-2026',
                            'category' => 'Reports',
                            'type' => 'PDF',
                            'icon' => 'fa-file-pdf',
                            'color' => '#e74c3c',
                            'date' => '2026-08-12',
                            'size' => '2.4',
                            'file' => 'annual-report-2025-2026.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'API Documentation – Kilele Tech Services',
                            'category' => 'Technical',
                            'type' => 'PDF',
                            'icon' => 'fa-file-code',
                            'color' => '#e74c3c',
                            'date' => '2026-07-18',
                            'size' => '1.8',
                            'file' => 'api-documentation.pdf',
                            'downloads' => 0
                        ],
                        [
                            'title' => 'Software Development Kit (SDK)',
                            'category' => 'Technical',
                            'type' => 'ZIP',
                            'icon' => 'fa-file-zipper',
                            'color' => '#f39c12',
                            'date' => '2026-07-10',
                            'size' => '5.6',
                            'file' => 'kilele-sdk.zip',
                            'downloads' => 0
                        ],
                    ];

                    foreach ($downloads as $d):
                        $downloadCount = isset($counts[$d['file']]) ? number_format($counts[$d['file']]) : '0';
                    ?>
                    <tr class="download-row" data-category="<?php echo $d['category']; ?>" data-type="<?php echo $d['type']; ?>" data-title="<?php echo strtolower($d['title']); ?>" style="border-bottom: 1px solid #f0f0f0; transition: 0.2s;">
                        <td style="padding: 14px 18px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas <?php echo $d['icon']; ?>" style="font-size: 1.2rem; color: <?php echo $d['color']; ?>; width: 20px;"></i>
                                <div>
                                    <div style="font-weight: 600; color: var(--kilele-navy);"><?php echo htmlspecialchars($d['title']); ?></div>
                                    <div style="font-size: 12px; color: #999; margin-top: 2px;">
                                        <i class="fas fa-download" style="font-size: 10px; color: var(--kilele-primary);"></i> 
                                        <span class="download-count-display"><?php echo $downloadCount; ?></span> downloads
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 18px;">
                            <span style="background: rgba(41, 160, 142, 0.08); color: var(--kilele-primary); padding: 4px 14px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                                <?php echo htmlspecialchars($d['category']); ?>
                            </span>
                        </td>
                        <td style="padding: 14px 18px;">
                            <div>
                                <div style="font-weight: 600; color: var(--kilele-navy); font-size: 13px;"><?php echo htmlspecialchars($d['type']); ?></div>
                                <div style="font-size: 12px; color: #999;"><?php echo htmlspecialchars($d['size']); ?> MB</div>
                            </div>
                        </td>
                        <td style="padding: 14px 18px; color: #666; font-size: 13px;">
                            <?php echo date('F d, Y', strtotime($d['date'])); ?>
                        </td>
                        <td style="padding: 14px 18px; text-align: center;">
                            <a href="download_secure.php?file=<?php echo urlencode($d['file']); ?>" class="download-btn" style="background: var(--kilele-primary); color: #fff; padding: 6px 18px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.3s;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- No Results Message -->
        <div id="noResults" style="display: none; text-align: center; padding: 40px 0; background: #f8fafb; border-radius: 12px; margin-top: 20px;">
            <i class="fas fa-search" style="font-size: 2.5rem; color: #ddd; margin-bottom: 12px;"></i>
            <h4 style="color: var(--kilele-navy);">No downloads found</h4>
            <p style="color: var(--kilele-text-light);">Try adjusting your search or filter criteria.</p>
        </div>

        <!-- Need Help? -->
        <div style="margin-top: 40px; padding: 24px; background: #f8fafb; border-radius: 12px; text-align: center; border: 1px solid #e9ecef;">
            <p style="color: var(--kilele-text-light); margin-bottom: 0;">Can't find what you're looking for? <a href="/contact.php" style="color: var(--kilele-primary); font-weight: 600; text-decoration: none;">Contact Support</a></p>
        </div>

    </div>
</section>

<script>
    // ============================================================
    // FILTER FUNCTION
    // ============================================================
    function filterDownloads() {
        const searchTerm = document.getElementById('searchDownloads').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const perPage = document.getElementById('perPageSelect').value;

        const rows = document.querySelectorAll('.download-row');
        let visibleCount = 0;
        let index = 0;

        rows.forEach(row => {
            const category = row.getAttribute('data-category');
            const type = row.getAttribute('data-type');
            const title = row.getAttribute('data-title');

            const matchCategory = categoryFilter === 'all' || category === categoryFilter;
            const matchType = typeFilter === 'all' || type === typeFilter;
            const matchSearch = title.includes(searchTerm);

            if (matchCategory && matchType && matchSearch) {
                visibleCount++;
                if (perPage === 'all') {
                    row.style.display = '';
                } else {
                    const limit = parseInt(perPage);
                    row.style.display = index < limit ? '' : 'none';
                }
                index++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('resultsCount').textContent = visibleCount;
        document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // ============================================================
    // INITIALIZE
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const total = document.querySelectorAll('.download-row').length;
        document.getElementById('resultsCount').textContent = total;
        filterDownloads();
    });
</script>

<?php include 'includes/footer.php'; ?>