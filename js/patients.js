const table = document.getElementById('patientsTable');
const searchInput = document.getElementById('patientSearch');
const pagination = document.getElementById('patientsPagination');

if (table) {
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const perPage = 8;
    let currentPage = 1;

    const draw = () => {
        const term = (searchInput?.value || '').toLowerCase();
        const filtered = rows.filter((row) => row.innerText.toLowerCase().includes(term));
        const pageCount = Math.max(1, Math.ceil(filtered.length / perPage));
        currentPage = Math.min(currentPage, pageCount);
        rows.forEach((r) => { r.style.display = 'none'; });
        filtered.slice((currentPage - 1) * perPage, currentPage * perPage).forEach((r) => {
            r.style.display = '';
        });

        if (pagination) {
            pagination.innerHTML = '';
            for (let p = 1; p <= pageCount; p += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = String(p);
                if (p === currentPage) btn.classList.add('active');
                btn.addEventListener('click', () => {
                    currentPage = p;
                    draw();
                });
                pagination.appendChild(btn);
            }
        }
    };

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            draw();
        });
    }
    draw();
}
