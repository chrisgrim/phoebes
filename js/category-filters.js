/**
 * Category Filters
 * Handles filtering of posts by subcategory on category archive pages
 */
document.addEventListener('DOMContentLoaded', function() {
    var filterButtons = document.querySelectorAll('.filter-btn');
    var mobileSelect = document.getElementById('mobile-category-filter');
    var posts = document.querySelectorAll('.post-item');

    function filterPosts(slug) {
        posts.forEach(function(post) {
            if (!slug) {
                post.style.display = 'block';
            } else {
                var cats = post.dataset.categories.split(' ');
                post.style.display = cats.includes(slug) ? 'block' : 'none';
            }
        });
    }

    // Desktop buttons
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            filterButtons.forEach(function(btn) {
                if (btn !== button) btn.classList.remove('active');
            });
            button.classList.toggle('active');

            var active = document.querySelector('.filter-btn.active');
            filterPosts(active ? active.dataset.category : '');

            // Sync mobile dropdown
            if (mobileSelect) {
                mobileSelect.value = active ? active.dataset.category : '';
            }
        });
    });

    // Mobile dropdown
    if (mobileSelect) {
        mobileSelect.addEventListener('change', function() {
            filterPosts(this.value);

            // Sync desktop buttons
            filterButtons.forEach(function(btn) {
                btn.classList.toggle('active', btn.dataset.category === mobileSelect.value);
            });
        });
    }
});

