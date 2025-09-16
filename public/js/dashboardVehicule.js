sessionStorage.setItem("user_id", "{{ session('user_id') }}");
sessionStorage.setItem("user_name", "{{ session('user_name') }}");

// fonction de filtrage combiné (filtre + recherche)
function filtrerVehicules() {
  const selectEl = document.querySelector('.filter select');
  const inputEl = document.querySelector('.search-box input');
  if (!selectEl || !inputEl) return;

  const rawFilter = selectEl.value || selectEl.textContent || '';
  const filterValue = normalizeText(singularizeFilter(rawFilter));
  const search = normalizeText(inputEl.value || '');

  const rows = document.querySelectorAll('#vehiculesTable tbody tr');
  let visibleCount = 0;

  rows.forEach(row => {
    const statutCell = row.querySelector('td:nth-child(5)');
    const nameCell = row.querySelector('td:nth-child(2) span') || row.querySelector('td:nth-child(2)');
    const statut = statutCell ? normalizeText(statutCell.textContent) : '';
    const name = nameCell ? normalizeText(nameCell.textContent) : '';

    const matchFiltre = (filterValue === 'tout') || statut === filterValue || statut.includes(filterValue);
    const matchSearch = name.includes(search);

    if (matchFiltre && matchSearch) {
      row.style.display = '';
      visibleCount++;
    } else {
      row.style.display = 'none';
    }
  });

  const tbody = document.querySelector('#vehiculesTable tbody');
  let noResultRow = document.getElementById('noResultRow');
  if (!noResultRow) {
    noResultRow = document.createElement('tr');
    noResultRow.id = 'noResultRow';
    noResultRow.innerHTML = `<td colspan="8" style="text-align:center; color:grey; padding:10px;">
      Aucun résultat trouvé
    </td>`;
    tbody.appendChild(noResultRow);
  }
  noResultRow.style.display = (visibleCount === 0 ? '' : 'none');
}

document.addEventListener('DOMContentLoaded', () => {
  const select = document.querySelector('.filter select');
  const searchInput = document.querySelector('.search-box input');

  if (select) select.addEventListener('change', filtrerVehicules);
  if (searchInput) searchInput.addEventListener('input', filtrerVehicules);

  filtrerVehicules();
});


// Filtrer les véhicules par disponibilité
function normalizeText(s) {
  if (!s) return '';
  return s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
}
function singularizeFilter(s) {
  if (!s) return s;
  s = s.trim().toLowerCase();
  if (s === 'tout') return 'tout';
  // retire un 's' terminal (Disponibles -> Disponible)
  if (s.endsWith('s')) return s.slice(0, -1);
  return s;
}

// supprimer un vehicule
$('.btn-delete').click(function(){
    let id = $(this).data('id');
    $('#deleteVehiculeId').val(id);
    $('#deleteVehiculeForm').attr('action', '/vehicules/' + id);
    $('#deleteVehiculeModal').show();
});

function closeDeleteModal(){
    $('#deleteVehiculeModal').hide();
}

// modifier un vehicule

// $('.btn-edit').click(function(){
//     let id = $(this).data('id');
//     $('#editVehiculeId').val(id);
//     $('#editMarque').val($(this).data('marque'));
//     $('#editModele').val($(this).data('modele'));
//     $('#editChauffeur').val($(this).data('chauffeur'));
//     $('#editDisponibilite').val($(this).data('disponibilite'));
//     $('#editPlacesTotal').val($(this).data('places_total'));

//     $('#editVehiculeModal').show();
// });

// function closeEditModal(){
//     $('#editVehiculeModal').hide();
// }

$('.btn-edit').click(function(){
    let id = $(this).data('id');

    // Remplir les champs
    $('#editVehiculeId').val(id);
    $('#editMarque').val($(this).data('marque'));
    $('#editModele').val($(this).data('modele'));
    $('#editChauffeur').val($(this).data('chauffeur'));
    $('#editDisponibilite').val($(this).data('disponibilite'));
    $('#editPlacesTotal').val($(this).data('places_total'));

    // 🔥 Définir dynamiquement l’action du formulaire
    $('#editVehiculeForm').attr('action', '/vehicules/' + id);

    // Afficher le modal
    $('#editVehiculeModal').show();
});
