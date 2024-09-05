const apiUrl = 'https://jsonplaceholder.typicode.com/todos';

const deleteModal = document.querySelector("#deleteModal");
const saveModal = document.querySelector("#saveModal");
const saveTaskform = document.querySelector('#saveTask');

/**
 * Populate the given template with its data
 * 
 * @param {string} templateSelector 
 * @param {object} data 
 */
function populateTemplate(templateSelector, data) {
    // Get template structure
    var template = document.querySelector(templateSelector);

    // Create the Handlebars.js compiler
    var compiler = Handlebars.compile(template.innerHTML);

    // Parse the HTML using the given data
    var parsedHtml = compiler({tasks: data});

    // Replace template with live data
    template.innerHTML = parsedHtml;

    // Unhide template
    template.classList.remove('d-none');
}

/**
 * Fetch all records from the API
 */
function fetchAllTasks() {
    fetch(apiUrl)
    // Convert API response to JSON
    .then((response) => response.json())
    // Proccess converted data
    .then((data) => {
        // Populate To Do column whith its tasks
        populateTemplate('#templateTodo', data);
        
        // Populate Done column whith its tasks
        populateTemplate('#templateDone', data);
    });
}

/**
 * Show and hide action alert
 * 
 * @param {string} type 
 * @param {string} message 
 */
function showAlert(type, message) {
    // Get template structure
    var template = document.querySelector('#alertTemplate');

    // Create the Handlebars.js compiler
    var compiler = Handlebars.compile(template.innerHTML);

    // Parse the HTML using the given data
    var parsedHtml = compiler({type: type, message: message});

    // Replace template with live data
    template.innerHTML = parsedHtml;
    
    // Show the hidden alert
    template.classList.remove('d-none');

    // Hide the alert after 3 seconds
    setTimeout(function() {
        template.classList.add('d-none');
    }, 3000);
}

/**
 * Hide given modal
 * 
 * @param {object} modal 
 */
function hideModal(modal) {
    const bootstrapModal = bootstrap.Modal.getInstance(modal);
    bootstrapModal.hide();
}

/**
 * Removes a task from the API
 * 
 * @param {number} id 
 */
function deleteTask(id) {
    fetch(`${apiUrl}/${id}`, {
        method: 'DELETE',
    })
    .then((response) => {
        if (response.ok) {
            document.querySelector(`#task${id}`).remove();
            showAlert('success', 'Task removed successfully!');
        } else {
            showAlert('danger', `Error removing task! ${response.statusText}`);
        }

        hideModal(deleteModal);
    });
}

/**
 * Saves a task in the API. If task ID is given update an existing task, otherwise
 * will create a new one
 * 
 * @param {number} id 
 * @param {string} title 
 * @param {string} completed 
 */
function saveTask(id = null, title, completed = 'false') {
    // Choose the right request method for update or create actions
    var requestMethod = id ? 'PUT' : 'POST';

    // Choose the right request URL for update or create actions
    var requestUrl = id ? `${apiUrl}/${id}` : apiUrl;

    // Create the request body object common to update and create actions
    var requestBody = {
        title: title,
        completed: completed,
        userId: 1,
    };

    // Add task ID if action is update
    if (id) {
        requestBody.id = id;
    }

    // Execute the proper API action, show status message and reload task lists
    fetch(requestUrl, {
        method: requestMethod,
        body: JSON.stringify(requestBody),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
    })
    .then((response) => {
        if (response.ok) {
            showAlert('success', 'Task saved successfully!');
            fetchAllTasks();
        } else {
            showAlert('danger', `Error saving task! ${response.statusText}`);
        }

        hideModal(saveModal);
    });
}

/**
 * Reset Save Form fields
 */
function resetSaveForm() {
    // Clear previous validations
    saveTaskform.classList.remove('was-validated');

    // Reset id field
    saveTaskform.elements["id"].value = null;

    // Reset title label
    saveTaskform.querySelector('#taskTitle').innerHTML = null;

    // Reset title field
    saveTaskform.elements["title"].value = null;

    // Reset completed field
    saveTaskform.elements["completed"].value = 'false';
}

// When DOM content finishes loading
window.addEventListener('DOMContentLoaded', (e) => {

    fetchAllTasks();

    // When Delete modal get opened
    deleteModal.addEventListener('shown.bs.modal', (e) => {
        // Get the task ID from the triggering button
        var taskId = e.relatedTarget.dataset.taskId;

        // Changes delete button action with the current task ID
        e.target.querySelector('#deleteBtn').setAttribute('onclick', `deleteTask(${taskId})`);
    });

    // When Save modal get opened, update its form fields according to the current task, or empty them if it is a new one
    saveModal.addEventListener('shown.bs.modal', (e) => {
        resetSaveForm();

        // Get the task ID from the triggering button
        var id = e.relatedTarget.dataset.taskId ?? null;

        // Save the taskId into the modal hidden field
        e.target.querySelector('#id').value = id;

        // Get the task title from the triggering button
        var title = e.relatedTarget.dataset.taskTitle ?? '';

        // Save the title into the modal hidden field
        e.target.querySelector('#title').value = title;

        if (title) {
            e.target.querySelector('#taskTitle').innerHTML = `<label>Title</label><div class="mb-3"><strong>Task ${id}</strong></div>`;
        }

        // Get the task status from the triggering button
        var completed = e.relatedTarget.dataset.taskCompleted ?? 'false';

        // Save the taskStatus into the modal hidden field
        e.target.querySelector('#completed').value = completed;
    });

    // Validate and/or submit Save Task form
    saveTaskform.addEventListener('submit', event => {
        event.preventDefault();
        event.stopPropagation();

        // If validated will proceed with submission
        if (saveTaskform.checkValidity()) {
            var id = saveTaskform.elements["id"].value ?? null;
            var title = saveTaskform.elements["title"].value;
            var completed = saveTaskform.elements["completed"].value;

            saveTask(id, title, completed);
        }

        saveTaskform.classList.add('was-validated');
    }, false);
});

