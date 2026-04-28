var Modal = new bootstrap.Modal("#exampleModal"); 
var triggers = document.querySelectorAll('.card-TaskItem-TaskTitle');

//comment section
triggers.forEach(button => { // add event listener to each button
    button.addEventListener('click', function(event) {
        event.stopPropagation();
        
        currentTaskId = this.dataset.taskId;
        
        document.getElementById('TaskTitleModal').innerHTML = this.innerText;
        
        var current_lang = this.dataset.currentLang;
        if (current_lang === 'ar') {
            document.getElementById('TaskDescriptionModal').innerHTML = this.dataset.taskArDesc;
        } else {
            document.getElementById('TaskDescriptionModal').innerHTML = this.dataset.taskEnDesc;
        }
        
        document.getElementById('commentTextarea').value = '';

        loadComments(currentTaskId);

        Modal.show();
    });
});

// submit CommentAdd event
document.getElementById('submitCommentAdd').addEventListener('click', function(event) {
    event.preventDefault();
    if (currentTaskId) {
        addComment(currentTaskId);
    } else {
        alert('Please select a task first');
    }
});

//Function to load comments from the database
function loadComments(taskID) {
    fetch("/TodoListApp/actions/tasks_comments/get_comments.php?task_id=" + taskID)
        .then(response => response.json())
        .then(commentsTable => {
            let commentsListSection = document.getElementById('commentsList');
            commentsListSection.innerHTML = ''; 

            if (commentsTable.length === 0) {
                commentsListSection.innerHTML = '<p class="text-muted"></p>';
            } else {
                commentsTable.forEach(comment => {
                    const likeIcon = comment.Like == 1
                        ? '<i class="fa-solid fa-heart" style="color: rgb(237, 67, 67);"></i>'
                        : '<i class="fa-regular fa-heart" style="color: rgb(237, 67, 67);"></i>';

                    commentsListSection.innerHTML += `
                        <div class="mb-3 p-2 border-bottom">
                            <!-- user name-->
                            <h6 class="fw-bold text-primary mb-1">You</h6>
                            <!-- Comment text -->
                            <p class="mb-2">${comment.comment}</p>
                            <!-- like button -->
                            <div class="small">
                                <a href="#!" class="text-danger" onclick="toggleLike(${comment.comment_ID}, ${comment.Like}, this); return false;">
                                    ${likeIcon}
                                    <span>Like</span>
                                </a>
                            </div>
                        </div>
                    `;
                });
            }
        })
        .catch(error => console.error('Error loading comments:', error));
}

// Toggle like on a comment
function toggleLike(commentID, currentStatus, element) {
    const newStatus = currentStatus == 1 ? 0 : 1;

    fetch("/TodoListApp/actions/tasks_comments/toggle_like.php?comment_id=" + commentID + "&status=" + newStatus)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = element.querySelector('i');
                if (newStatus === 1) {
                    icon.className = 'fa-solid fa-heart';
                } else {
                    icon.className = 'fa-regular fa-heart';
                }
                icon.style.color = 'rgb(237, 67, 67)';
                // Update onclick with the new status
                element.setAttribute('onclick', `toggleLike(${commentID}, ${newStatus}, this); return false;`);
            } else {
                alert(data.message || 'Error updating like');
            }
        })
        .catch(error => console.error('Error toggling like:', error));
}

function addComment(taskID) {
    let commentText = document.getElementById('commentTextarea').value.trim();
    if (commentText === '') {
        document.getElementById('commentTextareaError').classList.remove('d-none');
        return;
    } else {
        document.getElementById('commentTextareaError').classList.add('d-none');
    }
    
    const formData = new FormData();
    formData.append('comment', commentText);
    formData.append('task_id', taskID);
    
    fetch('/TodoListApp/actions/tasks_comments/add_comment.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.success) {
            document.getElementById('commentTextarea').value = '';
            loadComments(taskID); // Reload comments after adding
        } else {
            alert(data.message || 'Error adding comment');
        }
    }).catch(error => console.error('Error:', error));
}

// Hide the buttons if no items are selected
const checkboxsTasks = document.querySelectorAll('input[name="tasks[]"]');

for (const checkbox of checkboxsTasks) {
    checkbox.addEventListener('change', function() {
        const checkedCount = document.querySelectorAll('input[name="tasks[]"]:checked').length;
        toggleCompleteOrDeleteAllButtons(checkedCount);
    });
}

function toggleCompleteOrDeleteAllButtons(count) {
    const CompleteDeleteAllButtons = document.querySelectorAll("#bulkBtn");
    if (count > 0) {
        CompleteDeleteAllButtons.forEach(button => {
            button.classList.remove('d-none');
        });
    } else {
        CompleteDeleteAllButtons.forEach(button => {
            button.classList.add('d-none');
        });
    }
}