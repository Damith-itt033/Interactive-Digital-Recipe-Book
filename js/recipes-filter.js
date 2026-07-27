//Recipe Search & Filter Module (js/recipes-filter.js)

document.addEventListener('DOMContentLoaded', () => {

  // 1. Category Pill Toggle (JS Feature 1: Dynamic Content Filtering)
  const filterPills = document.querySelectorAll('.filter-pill');
  const recipeWrappers = document.querySelectorAll('.recipe-card-wrapper');

  if (filterPills.length > 0 && recipeWrappers.length > 0) {
    filterPills.forEach(pill => {
      pill.addEventListener('click', () => {
        filterPills.forEach(p => p.classList.remove('active'));
        pill.classList.add('active');

        const selectedCat = pill.getAttribute('data-category');

        recipeWrappers.forEach(card => {
          const cardCat = card.getAttribute('data-category');
          if (selectedCat === 'all' || cardCat === selectedCat) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // 2. Directory Keyword Live Search
  const directorySearchInput = document.getElementById('recipeDirectorySearch');
  const noResultsAlert = document.getElementById('noResultsAlert');
  const recipeCountBadge = document.getElementById('recipeCountBadge');
  const categoryCheckboxes = document.querySelectorAll('.filter-category-chk');
  const btnResetFilters = document.getElementById('btnResetFilters');

  function updateDirectoryFilter() {
    if (!recipeWrappers.length) return;

    const query = directorySearchInput ? directorySearchInput.value.toLowerCase().trim() : '';
    
    // Checked categories
    const checkedCategories = Array.from(categoryCheckboxes)
      .filter(chk => chk.checked)
      .map(chk => chk.value);

    let visibleCount = 0;

    recipeWrappers.forEach(card => {
      const cardTitle = card.getAttribute('data-title') || '';
      const cardCat = card.getAttribute('data-category') || '';

      const matchesSearch = query === '' || cardTitle.includes(query);
      const matchesCategory = checkedCategories.length === 0 || checkedCategories.includes(cardCat);

      if (matchesSearch && matchesCategory) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (recipeCountBadge) {
      recipeCountBadge.innerHTML = `<i class="fa-solid fa-layer-group me-1 text-warning"></i> Showing ${visibleCount} Recipes`;
    }

    if (noResultsAlert) {
      if (visibleCount === 0) {
        noResultsAlert.classList.remove('d-none');
      } else {
        noResultsAlert.classList.add('d-none');
      }
    }
  }

  if (directorySearchInput) {
    directorySearchInput.addEventListener('keyup', updateDirectoryFilter);
  }

  if (categoryCheckboxes.length > 0) {
    categoryCheckboxes.forEach(chk => {
      chk.addEventListener('change', updateDirectoryFilter);
    });
  }

  if (btnResetFilters) {
    btnResetFilters.addEventListener('click', () => {
      if (directorySearchInput) directorySearchInput.value = '';
      categoryCheckboxes.forEach(chk => chk.checked = false);
      updateDirectoryFilter();
    });
  }

  // 3. Heart Favorite Toggle (JS Feature 4: Interactive Event Handling)
  const favHeartButtons = document.querySelectorAll('.fav-heart-btn');
  favHeartButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      btn.classList.toggle('active');
      const icon = btn.querySelector('i');
      if (btn.classList.contains('active')) {
        icon.style.color = '#ef4444';
      } else {
        icon.style.color = '#64748b';
      }
    });
  });

});
