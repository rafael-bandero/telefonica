<?php
require_once('Task.php');

use Telefonica\Task;

Task::fetchAll();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>To-Do List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/custom.css" rel="stylesheet">
</head>

<body>
    <header class="telefonica">
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="images/logo.png" width="34" height="34" title="Movistar Logo">
                </a>

                <div class="btn-group" role="group">
                    <button type="button" class="btn nav-link p-2" title="Search"><i class="bi bi-search"></i></button>
                    <button type="button" class="btn nav-link p-2" title="Configs"><i class="bi bi-sliders"></i></button>
                    <button type="button" class="btn nav-link p-2" title="Close"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </nav>
    </header>

    <div class="container pt-4">
        <h6 class="text-uppercase">Project</h6>

        <div id="alertTemplate" class="d-none">
            <div id="alert" class="alert alert-{{type}}" role="alert">
                {{message}}
            </div>
        </div>

        <div class="row">
            <!-- To Do column -->
            <div class="col-sm-5 col-md-4 col-xl-3">
                <div class="py-4">
                    <span class="h3 lh-base">To Do</span>
                    <button type="button" class="btn telefonica rounded-circle float-end" title="Create New"
                        data-bs-toggle="modal" data-bs-target="#saveModal" data-task-completed="false"><i class="bi bi-plus-lg"></i></button>
                </div>

                <div id="templateTodo" class="d-none">
                    {{#each tasks}}
                    {{#if this.completed}}
                    {{false}}
                    {{else}}
                    <div id="task{{this.id}}" class="card mb-4">
                        <div class="card-body p-3">
                            <span class="badge alert alert-danger p-1">State</span>
                            <div class="btn-group float-end">
                                <button type="button" class="btn text-muted py-0 px-1" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#saveModal" data-task-id="{{this.id}}" data-task-title="{{this.title}}"
                                    data-task-completed="{{this.completed}}"><i class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn text-muted p-0" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-task-id="{{this.id}}"><i class="bi bi-trash"></i></button>
                            </div>
                            <h5>Task {{this.id}}</h5>
                            <p>{{this.title}}</p>
                        </div>
                    </div>
                    {{/if}}
                    {{/each}}
                </div>
            </div>
            <!-- end To Do column -->

            <!-- Vertical separator -->
            <div class="vr p-0 mx-4 d-none d-sm-block"></div>

            <!-- Done column -->
            <div class="col-sm-5 col-md-4 col-xl-3">
                <div class="py-4">
                    <span class="h3 lh-base">Done</span>
                    <button type="button" class="btn telefonica rounded-circle float-end" title="Create New"
                        data-bs-toggle="modal" data-bs-target="#saveModal" data-task-completed="true"><i class="bi bi-plus-lg"></i></button>
                </div>

                <div id="templateDone" class="d-none">
                    {{#each tasks}}
                    {{#if this.completed}}
                    <div id="task{{this.id}}" class="card mb-4">
                        <div class="card-body p-3">
                            <span class="badge alert alert-success p-1">State</span>
                            <div class="btn-group float-end">
                                <button type="button" class="btn text-muted py-0 px-1" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#saveModal" data-task-id="{{this.id}}" data-task-title="{{this.title}}"
                                    data-task-completed="{{this.completed}}"><i class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn text-muted p-0" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-task-id="{{this.id}}"><i class="bi bi-trash"></i></button>
                            </div>
                            <h5>Task {{this.id}}</h5>
                            <p>{{this.title}}</p>
                        </div>
                    </div>
                    {{/if}}
                    {{/each}}
                </div>
            </div>
            <!-- end Done column -->
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Alert</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to permanently delete this task?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="taskId" name="taskId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-ban"></i> Close</button>
                    <button id="deleteBtn" type="button" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Delete Modal -->

    <!-- Save Modal -->
    <div class="modal fade" id="saveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="saveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="saveTask" class="needs-validation" novalidate>
                    <div class="modal-header text-bg-primary">
                        <h1 class="modal-title fs-5" id="saveModalLabel"><i class="bi bi-pencil-square"></i> Save Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="taskTitle"></div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Status</label>
                            <select id="completed" name="completed" class="form-select" required>
                                <option value="false" selected>False</option>
                                <option value="true">True</option>
                              </select>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Task Description</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-ban"></i> Close</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Save Modal -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
    <script src="js/php.js"></script>
    <script src="js/common.js"></script>
</body>

</html>