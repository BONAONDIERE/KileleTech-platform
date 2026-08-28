<?php
$pageTitle = 'Our Team – KileleTech';
include 'includes/header.php';
?>

<!-- ============================================================
   PAGE HERO – Uses global styles from layout.css
   ============================================================ -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__breadcrumb"><a href="index.php">Home</a> / About Us / Our Team</div>
        <h1 class="page-hero__title">Our ICT Team</h1>
        <p class="page-hero__subtitle">
            The talented professionals behind KileleTech's technology solutions.
        </p>
    </div>
</section>

<!-- ============================================================
   ICT TEAM
   ============================================================ -->
<section class="content-section" style="padding: 20px 0 50px;">
    <div class="container">
        
        <!-- Compact introduction -->
        <div style="text-align: center; margin-bottom: 25px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <p style="color: var(--kilele-text-light); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                <span style="font-weight: 700; color: var(--kilele-navy);">Meet our leadership</span> — guiding KileleTech with strategic vision and a commitment to innovative technology solutions.
            </p>
        </div>

        <div class="team-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">

            <!-- Faith Maina (Director) -->
            <div class="team-card" data-id="1" data-color="teal" style="background: #fff; border-radius: 16px; padding: 30px 20px 24px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.06); transition: all 0.3s ease; border-top: 4px solid var(--kilele-primary); cursor: pointer; position: relative; grid-column: 2;">
                <div class="team-card__avatar" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; transition: 0.3s; border: 4px solid var(--kilele-primary); display: flex; align-items: center; justify-content: center; background: var(--kilele-primary); color: #fff; font-size: 3rem; font-weight: 800;">
                    FM
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 4px;">Faith Maina</h4>
                <span style="display: inline-block; font-size: 13px; font-weight: 600; color: var(--kilele-primary); background: rgba(41, 160, 142, 0.1); padding: 4px 16px; border-radius: 50px; margin-bottom: 10px;">Director</span>
                <p style="font-size: 0.9rem; color: var(--kilele-text-light); margin: 0; line-height: 1.5;">
                    Leads KileleTech with a strategic vision, overseeing operations, partnerships, and technology growth.
                </p>
                <div style="margin-top: 12px; font-size: 12px; color: var(--kilele-primary); font-weight: 600;">
                    Click to learn more <i class="fas fa-arrow-right ms-1"></i>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
   TEAM MEMBER MODAL
   ============================================================ -->
<div id="teamModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #fff; border-radius: 18px; padding: 35px; max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; position: relative; animation: modalFadeIn 0.3s ease;">
        <button onclick="closeModal()" style="position: absolute; top: 12px; right: 18px; background: none; border: none; font-size: 28px; color: #999; cursor: pointer; transition: 0.3s;">&times;</button>
        <div id="modalContent"></div>
    </div>
</div>

<script>
const teamData = [
    {
        id: 1,
        name: 'Faith Maina',
        role: 'Director',
        avatar: 'FM',
        color: 'teal',
        bio: 'Faith is the Director at KileleTech, bringing a strategic vision and robust leadership to the company. With a passion for technology and business growth, she oversees the daily operations, ensures client satisfaction, and drives the long-term strategic direction of the organization.\n\nWith a deep understanding of the ICT landscape, Faith is dedicated to building strong relationships with clients, vendors, and partners, ensuring that KileleTech remains a trusted and innovative provider of technology solutions. She is committed to fostering a culture of excellence, integrity, and continuous learning within the team, helping clients harness the power of technology to achieve their goals.',
        qualifications: ['BSc Information Technology', 'MBA Leadership & Management', 'Certified Project Manager (PMP)'],
        skills: ['Strategic Planning', 'Business Development', 'Operations Management', 'Client Relations', 'Leadership'],
        email: 'faith@kileletech.com',
        phone: '+254 700 000 000'
    }
];

function openModal(id) {
    const member = teamData.find(m => m.id === id);
    if (!member) return;

    const modal = document.getElementById('teamModal');
    const content = document.getElementById('modalContent');

    const qualificationsHtml = member.qualifications.map(qual => 
        `<span style="display: inline-block; background: rgba(41, 160, 142, 0.08); color: var(--kilele-primary); padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; margin: 3px 4px 3px 0;">${qual}</span>`
    ).join('');

    const skillsHtml = member.skills.map(skill => 
        `<span style="display: inline-block; background: rgba(201, 88, 115, 0.08); color: var(--kilele-coral); padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; margin: 3px 4px 3px 0;">${skill}</span>`
    ).join('');

    const bioHtml = member.bio.replace(/\n\n/g, '</p><p style="color: var(--kilele-text-light); line-height: 1.7; font-size: 0.9rem; margin-bottom: 10px;">').replace(/\n/g, '<br>');

    content.innerHTML = `
        <div style="text-align: center; margin-bottom: 16px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; margin: 0 auto 14px; border: 3px solid var(--kilele-primary); display: flex; align-items: center; justify-content: center; background: var(--kilele-primary); color: #fff; font-size: 2.5rem; font-weight: 800;">
                ${member.avatar}
            </div>
            <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--kilele-navy); margin-bottom: 2px;">${member.name}</h3>
            <span style="display: inline-block; font-size: 13px; font-weight: 600; color: var(--kilele-primary); background: rgba(41, 160, 142, 0.1); padding: 4px 16px; border-radius: 50px; margin-bottom: 10px;">
                ${member.role}
            </span>
        </div>

        <div style="margin-bottom: 14px;">
            <h5 style="font-weight: 700; color: var(--kilele-navy); margin-bottom: 4px; font-size: 0.95rem;">About</h5>
            <p style="color: var(--kilele-text-light); line-height: 1.7; font-size: 0.9rem; margin-bottom: 8px;">${bioHtml}</p>
        </div>

        <div style="margin-bottom: 14px;">
            <h5 style="font-weight: 700; color: var(--kilele-navy); margin-bottom: 4px; font-size: 0.95rem;">Qualifications</h5>
            <div>${qualificationsHtml}</div>
        </div>

        <div style="margin-bottom: 14px;">
            <h5 style="font-weight: 700; color: var(--kilele-navy); margin-bottom: 4px; font-size: 0.95rem;">Skills & Expertise</h5>
            <div>${skillsHtml}</div>
        </div>

        <div style="border-top: 1px solid #eee; padding-top: 14px;">
            <h5 style="font-weight: 700; color: var(--kilele-navy); margin-bottom: 4px; font-size: 0.95rem;">Connect</h5>
            <p style="color: var(--kilele-text-light); font-size: 0.85rem; margin: 2px 0;">
                <i class="fas fa-envelope" style="color: var(--kilele-primary); width: 24px;"></i> 
                <a href="mailto:${member.email}" style="color: var(--kilele-text-light); text-decoration: none;">${member.email}</a>
            </p>
            <p style="color: var(--kilele-text-light); font-size: 0.85rem; margin: 2px 0;">
                <i class="fas fa-phone" style="color: var(--kilele-primary); width: 24px;"></i> 
                ${member.phone}
            </p>
        </div>

        <div style="margin-top: 16px; text-align: center;">
            <button onclick="closeModal()" style="padding: 8px 32px; border-radius: 50px; font-weight: 600; background: var(--kilele-primary); color: #fff; border: none; cursor: pointer; transition: 0.3s; font-size: 0.9rem;">
                Close
            </button>
        </div>
    `;

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('teamModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.team-card');
    cards.forEach(card => {
        const id = parseInt(card.dataset.id);
        card.addEventListener('click', function() {
            openModal(id);
        });
    });

    document.getElementById('teamModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(-15px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.team-card[data-color="teal"]:hover { border-top-color: var(--kilele-primary); }
.team-card[data-color="yellow"]:hover { border-top-color: var(--kilele-yellow); }
.team-card[data-color="coral"]:hover { border-top-color: var(--kilele-coral); }

.team-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}
.team-card:hover .team-card__avatar {
    transform: scale(1.05);
    border-color: var(--kilele-primary) !important;
}

@media (max-width: 768px) {
    .team-grid { grid-template-columns: 1fr !important; gap: 20px !important; }
}
@media (max-width: 480px) {
    .team-grid { grid-template-columns: 1fr !important; }
    .team-card__avatar { width: 80px !important; height: 80px !important; }
    #teamModal > div { padding: 24px; margin: 10px; }
}
</style>

<?php include 'includes/footer.php'; ?>