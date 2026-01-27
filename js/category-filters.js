/**
 * Category Filters
 * Handles filtering of posts by subcategory on category archive pages
 */
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const posts = document.querySelectorAll('.post-item');

    // Early return if no filter buttons exist
    if (filterButtons.length === 0) {
        return;
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all other buttons
            filterButtons.forEach(btn => {
                if (btn !== this) {
                    btn.classList.remove('active');
                }
            });

            // Toggle current button
            this.classList.toggle('active');
            
            // If no buttons are active, show all posts
            const activeButtons = document.querySelectorAll('.filter-btn.active');
            if (activeButtons.length === 0) {
                posts.forEach(post => post.style.display = 'block');
                return;
            }
            
            // Filter posts based on active category
            const selectedCategory = this.dataset.category;
            posts.forEach(post => {
                const postCategories = post.dataset.categories.split(' ');
                const shouldShow = postCategories.includes(selectedCategory);
                post.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });
});

