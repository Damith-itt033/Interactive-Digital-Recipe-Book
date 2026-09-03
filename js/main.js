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