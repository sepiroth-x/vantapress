// VP Social Theme - JavaScript
// Handles interactive features for the social networking theme

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Alpine.js for dropdowns and modals
    console.log('VP Social Theme loaded');
    
    // Auto-resize textarea
    const textareas = document.querySelectorAll('textarea.auto-resize');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Image preview on upload
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = input.closest('form').querySelector('.image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
    
    // Like/Reaction toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.reaction-toggle')) {
            e.preventDefault();
            const button = e.target.closest('.reaction-toggle');
            const postId = button.dataset.postId;
            const reactionType = button.dataset.reactionType || 'like';
            
            toggleReaction(postId, reactionType);
        }
    });
    
    // Comment submission
    const commentForms = document.querySelectorAll('.comment-form');
    commentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitComment(form);
        });
    });
    
    // Infinite scroll for newsfeed
    let page = 1;
    let loading = false;
    
    if (document.querySelector('.newsfeed')) {
        window.addEventListener('scroll', function() {
            if (loading) return;
            
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.body.offsetHeight - 500;
            
            if (scrollPosition >= threshold) {
                loadMorePosts();
            }
        });
    }
    
    // Functions
    function toggleReaction(postId, reactionType) {
        fetch('/social/reactions/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reactable_type: 'post',
                reactable_id: postId,
                type: reactionType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateReactionUI(postId, data.count, data.userReacted);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function submitComment(form) {
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add comment to UI
                const commentsList = form.closest('.post-card').querySelector('.comments-list');
                if (commentsList) {
                    commentsList.insertAdjacentHTML('beforeend', data.commentHtml);
                }
                // Clear form
                form.reset();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateReactionUI(postId, count, userReacted) {
        const button = document.querySelector(`.reaction-toggle[data-post-id="${postId}"]`);
        if (button) {
            const countSpan = button.querySelector('.reaction-count');
            if (countSpan) {
                countSpan.textContent = count;
            }
            
            if (userReacted) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        }
    }
    
    function loadMorePosts() {
        loading = true;
        page++;
        
        fetch(`/social/newsfeed?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const feed = document.querySelector('.newsfeed');
            if (feed && html.trim()) {
                feed.insertAdjacentHTML('beforeend', html);
            }
            loading = false;
        })
        .catch(error => {
            console.error('Error:', error);
            loading = false;
        });
    }
    
    // Notification polling (every 30 seconds)
    if (document.querySelector('.notification-icon')) {
        setInterval(checkNotifications, 30000);
    }
    
    function checkNotifications() {
        fetch('/social/notifications/count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (badge && data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else if (badge) {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Message polling for conversations
    if (document.querySelector('.conversation-view')) {
        const conversationId = document.querySelector('.conversation-view').dataset.conversationId;
        setInterval(() => checkNewMessages(conversationId), 5000);
    }
    
    function checkNewMessages(conversationId) {
        const messagesContainer = document.querySelector('.messages-container');
        if (!messagesContainer) return;
        
        const lastMessageId = messagesContainer.dataset.lastMessageId;
        
        fetch(`/social/messages/${conversationId}/new?after=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        messagesContainer.insertAdjacentHTML('beforeend', message.html);
                        messagesContainer.dataset.lastMessageId = message.id;
                    });
                    // Scroll to bottom
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
