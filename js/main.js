//Main JavaScript Module (js/main.js)

document.addEventListener('DOMContentLoaded', () => {

  // JS Feature 5: Floating Back-To-Top Button Logic
  const btnBackToTop = document.getElementById('btnBackToTop');
  if (btnBackToTop) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        btnBackToTop.style.display = 'flex';
      } else {
        btnBackToTop.style.display = 'none';
      }
    });

    btnBackToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Recipe Catalog Sample Database (JS Feature 4 - Modal Dynamic Population)
  const recipeDataStore = {
    1: {
      title: "Ultimate Homemade Creamy Ramen",
      category: "LUNCH",
      badgeClass: "bg-warning text-dark",
      image: "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80",
      description: "Master the secrets of authentic Japanese tonkotsu ramen broth from home with basic, easy-to-find ingredients.",
      ingredients: [
        "2 packs Ramen noodles",
        "4 cups Chicken / Pork bone broth",
        "2 Soft-boiled eggs (halved)",
        "2 tbsp Miso paste & soy sauce",
        "Fresh spring onions & sesame seeds",
        "Norisheets & chili oil"
      ],
      steps: [
        "Simmer broth with miso paste, soy sauce, and grated garlic for 15 minutes.",
        "Boil ramen noodles separately for 3 minutes until al dente.",
        "Pour hot broth into serving bowls, add strained noodles, and top with soft-boiled eggs, spring onions, and chili oil."
      ]
    },
    2: {
      title: "Fresh Garden Avocado Salad",
      category: "BREAKFAST",
      badgeClass: "bg-success text-white",
      image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80",
      description: "A vibrant, nutrient-dense keto breakfast bowl packed with fresh avocado, cherry tomatoes, and lemon dressing.",
      ingredients: [
        "2 ripe Avocados (diced)",
        "3 cups Fresh mixed salad greens",
        "1 cup Cherry tomatoes (halved)",
        "2 tbsp Extra virgin olive oil & lemon juice",
        "50g Feta cheese & toasted walnuts"
      ],
      steps: [
        "Wash and dry mixed salad greens thoroughly.",
        "In a small bowl, whisk olive oil, fresh lemon juice, salt, and black pepper.",
        "Combine greens, diced avocado, tomatoes, feta, and walnuts in a large bowl. Drizzle dressing before serving."
      ]
    },
    3: {
      title: "Chocolate Lava Soufflé",
      category: "DESSERT",
      badgeClass: "bg-danger text-white",
      image: "https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80",
      description: "Decadent rich cocoa lava cake with a warm molten center that melts effortlessly with every spoon.",
      ingredients: [
        "150g Dark chocolate (70% cocoa)",
        "100g Unsalted butter",
        "3 Eggs + 2 Egg yolks",
        "50g Fine castor sugar",
        "30g All-purpose flour"
      ],
      steps: [
        "Melt dark chocolate and butter over a double boiler until smooth.",
        "Whisk eggs, egg yolks, and sugar together until pale and thick.",
        "Fold melted chocolate and flour gently. Pour into buttered ramekins and bake at 200°C for 12 minutes."
      ]
    },
    4: {
      title: "Mediterranean Grilled Chicken",
      category: "DINNER",
      badgeClass: "bg-primary text-white",
      image: "https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=800&q=80",
      description: "Herb-marinated grilled chicken breasts infused with fresh garlic, rosemary, and olive oil.",
      ingredients: [
        "4 Boneless chicken breasts",
        "3 tbsp Olive oil & lemon zest",
        "3 cloves Garlic (minced)",
        "1 tbsp Fresh rosemary & oregano",
        "Salt & cracked black pepper"
      ],
      steps: [
        "Whisk olive oil, lemon zest, garlic, rosemary, salt, and pepper in a marinade bowl.",
        "Marinate chicken breasts for at least 30 minutes.",
        "Grill over medium-high heat for 6-7 minutes per side until internal temperature reaches 75°C."
      ]
    }
  };

  // JS Feature 4: Modal Event Handling & Dynamic Content Injection
  const openRecipeButtons = document.querySelectorAll('.open-recipe-btn');
  const recipeModalEl = document.getElementById('recipeModal');

  if (recipeModalEl && openRecipeButtons.length > 0) {
    const bsModal = new bootstrap.Modal(recipeModalEl);

    openRecipeButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const id = btn.getAttribute('data-recipe-id');
        const data = recipeDataStore[id];

        if (data) {
          document.getElementById('modalRecipeTitle').textContent = data.title;
          document.getElementById('modalRecipeImg').src = data.image;
          document.getElementById('modalRecipeImg').alt = data.title;

          const badgeEl = document.getElementById('modalRecipeBadge');
          badgeEl.textContent = data.category;
          badgeEl.className = `badge ${data.badgeClass} mb-2`;

          document.getElementById('modalRecipeDesc').textContent = data.description;

          // Render Ingredients
          const ingrUl = document.getElementById('modalRecipeIngredients');
          ingrUl.innerHTML = '';
          data.ingredients.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            ingrUl.appendChild(li);
          });

          // Render Steps
          const stepsOl = document.getElementById('modalRecipeSteps');
          stepsOl.innerHTML = '';
          data.steps.forEach(step => {
            const li = document.createElement('li');
            li.textContent = step;
            stepsOl.appendChild(li);
          });

          bsModal.show();
        }
      });
    });
  }

});
