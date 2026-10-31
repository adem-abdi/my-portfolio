(() => {
    'use strict';

    const sections = ['dashboard', 'hero', 'services', 'projects', 'skills', 'testimonials', 'contact', 'messages'];
    const sidebar = document.getElementById('adminSidebar');
    const mobileToggle = document.getElementById('mobileToggle');

    function showSection(section, updateHash = true) {
        const selected = sections.includes(section) ? section : 'dashboard';
        document.querySelectorAll('[data-panel]').forEach(panel => {
            panel.classList.toggle('active', panel.dataset.panel === selected);
        });
        document.querySelectorAll('[data-section]').forEach(link => {
            link.classList.toggle('active', link.dataset.section === selected);
        });
        if (updateHash && window.location.hash !== `#${selected}`) {
            history.replaceState(null, '', `#${selected}`);
        }
        if (sidebar) sidebar.classList.remove('open');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('[data-section]').forEach(control => {
        control.addEventListener('click', event => {
            event.preventDefault();
            showSection(control.dataset.section);
        });
    });

    window.addEventListener('hashchange', () => showSection(window.location.hash.replace('#', ''), false));
    showSection(window.location.hash.replace('#', '') || 'dashboard', false);

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', event => {
            if (sidebar.classList.contains('open') && !sidebar.contains(event.target) && event.target !== mobileToggle) {
                sidebar.classList.remove('open');
            }
        });
    }

    function itemMarkup(type, key) {
        const prefix = `items[${key}]`;
        const remove = `<div class="editor-card-top"><div><span class="eyebrow">New ${type}</span><span class="item-index">Draft</span></div><label class="delete-toggle"><input type="checkbox" name="${prefix}[_delete]" value="1"><span>Remove</span></label></div>`;
        const field = (label, name, options = '') => `<label class="field"><span>${label}</span><input ${options} name="${prefix}[${name}]" value=""></label>`;
        const textarea = (label, name) => `<label class="field full"><span>${label}</span><textarea name="${prefix}[${name}]" rows="3"></textarea></label>`;
        const order = field('Display order', 'sort_order', 'type="number" min="1" value="99"');

        if (type === 'Service') {
            return `<div class="editor-card" data-repeatable-item>${remove}<div class="form-grid two-col">${field('Title', 'title', 'required')}${order}${textarea('Description', 'description')}</div><label class="switch-field"><input type="checkbox" name="${prefix}[highlighted]" value="1"><span class="switch"></span><span><strong>Highlight this service</strong><small>Uses the portfolio’s featured card treatment.</small></span></label></div>`;
        }
        if (type === 'Project') {
            return `<div class="editor-card" data-repeatable-item>${remove}<div class="form-grid two-col">${field('Project title', 'title', 'required')}${field('Date', 'project_date', 'type="date"')}${textarea('Description', 'description')}${field('GitHub URL', 'github_link', 'type="url"')}${field('Demo URL', 'demo_link', 'type="url"')}${field('Technology stack', 'tech_stack')}${order}${field('Image URL or relative path', 'image_path')}${`<label class="field"><span>Upload project image</span><input type="file" name="project_image_${key}" accept="image/jpeg,image/png,image/webp,image/gif"></label>`}</div><label class="switch-field"><input type="checkbox" name="${prefix}[featured]" value="1"><span class="switch"></span><span><strong>Featured project</strong><small>Marks the project as a priority item.</small></span></label></div>`;
        }
        if (type === 'Skill') {
            return `<div class="editor-card" data-repeatable-item>${remove}<div class="form-grid three-col">${field('Skill name', 'name', 'required')}${field('Category', 'category', 'required')}${field('Proficiency (0–100)', 'percentage', 'type="number" min="0" max="100" value="80"')}${order}</div></div>`;
        }
        return `<div class="editor-card" data-repeatable-item>${remove}<div class="form-grid two-col">${field('Client name', 'client_name', 'required')}${field('Client title', 'client_title')}${textarea('Review', 'testimonial_text')}${field('Rating (1–5)', 'rating', 'type="number" min="1" max="5" value="5"')}${order}</div></div>`;
    }

    const typeMap = { service: 'Service', project: 'Project', skill: 'Skill', testimonial: 'Testimonial' };
    document.querySelectorAll('[data-add]').forEach(button => {
        button.addEventListener('click', () => {
            const type = typeMap[button.dataset.add];
            const list = button.closest('form').querySelector('[data-list]');
            const key = `new_${Date.now()}`;
            list.insertAdjacentHTML('beforeend', itemMarkup(type, key));
            const newCard = list.lastElementChild;
            newCard.classList.add('is-new');
            newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const firstInput = newCard.querySelector('input:not([type="checkbox"]), textarea');
            if (firstInput) firstInput.focus();
        });
    });

    document.addEventListener('change', event => {
        const checkbox = event.target.closest('.delete-toggle input');
        if (checkbox) {
            const card = checkbox.closest('[data-repeatable-item]');
            if (card) card.classList.toggle('is-removed', checkbox.checked);
        }
    });

    document.querySelectorAll('.editor-form').forEach(form => {
        form.addEventListener('submit', event => {
            const submit = form.querySelector('button[type="submit"]');
            if (submit) {
                submit.disabled = true;
                submit.classList.add('is-saving');
                submit.dataset.originalText = submit.innerHTML;
                submit.innerHTML = 'Saving…';
            }
            const section = form.closest('[data-panel]')?.dataset.panel;
            if (section) history.replaceState(null, '', `#${section}`);
        });
    });
})();
