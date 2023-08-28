<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo-List App</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.6.2/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body class="w-full min-h-screen bg-indigo-400 flex justify-center items-center md:px-[10%] px-[5%]">
    {{-- todo list --}}
    <div class="rounded-md shadow-lg p-5 bg-white w-full max-w-[600px] flex flex-col">
        <div class="flex justify-between items-center">
            <h1 class="text-[20px] font-semibold">My Todo-List</h1>
            <button class="btn btn-primary" id="showModalAdd">Add</button>
        </div>
        <div class="w-full">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="myList">
                        @foreach ($myList as $item)
                            <tr>
                                <td>{{ $item['todoName'] }}</td>
                                <td>{{ $item['updated_at']->format('M j, Y, h:i A') }}</td>
                                <td>
                                    <form class="w-full flex gap-2">
                                        <button type="submit" value="{{ $item['id'] }}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                        <button type="submit" value="{{ $item['id'] }}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- modal add --}}
    <div class="w-full min-h-screen bg-black/30 fixed top-0 justify-center items-center hidden" id="modalAdd">
        <form class="modal-box">
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" id="hideModalAdd">✕</button>
            <h3 class="font-bold text-lg mb-5">Add Todo-List</h3>
            <div class="mb-3 flex flex-col gap-1">
                <input type="text" name="todoName" class="input input-bordered w-full" placeholder="Todo name">
                <p id="errorTodoName" class="text-[14px] text-red-500"></p>
            </div>
            <button type="submit" class="btn btn-primary btn-active" id="addList">Add</button>
        </form>
    </div>
    {{-- modal edit --}}
    <div class="w-full min-h-screen bg-black/30 fixed top-0 justify-center items-center hidden" id="modalEdit">
        <form class="modal-box">
            <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" id="hideModalEdit">✕</button>
            <h3 class="font-bold text-lg mb-5">Edit Todo-List</h3>
            <div class="mb-3 flex flex-col gap-1">
                <input type="hidden" name="todoId"/>
                <input type="text" name="todoName" class="input input-bordered w-full" placeholder="Todo name">
                <p id="errorTodoName" class="text-[14px] text-red-500"></p>
            </div>
            <button type="submit" class="btn btn-primary btn-active" id="editSave">Save</button>
        </form>
    </div>
    {{-- script --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function () {
            // class request
            class Request {
                // addition of todo
                add(todoName) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('add') }}",
                        data: {todoName:todoName},
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            console.log(response)
                            if(response.status == 200) {
                                // reset error messages
                                $("#errorTodoName").html("");
                                // hide modal and reset todo name input value
                                $("#modalAdd").addClass("hidden").removeClass("flex");
                                $("#modalAdd input[name=todoName]").val("");
                                Toastify({
                                    text: "Add success",
                                    className: "info",
                                    style: {
                                        background: "#22c55e",
                                    }
                                }).showToast();
                                const formattedTime = changeTimeFormat(response.list.updated_at)
                                $("#myList").append(
                                    `<tr>
                                        <td>
                                            ${response.list.todoName}
                                        </td>
                                        <td>
                                            ${formattedTime}
                                        </td>
                                        <td>
                                            <form class="w-full flex gap-2">
                                                <button type="submit" value="${response.list.id}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                                <button type="submit" value="${response.list.id}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                            </form>
                                        </td>
                                    </tr>`
                                );
                            } else {
                                Toastify({
                                    text: "Add failed",
                                    className: "info",
                                    style: {
                                        background: "#ef4444",
                                    }
                                }).showToast();
                                $("#errorTodoName").html(response.errorMessages.todoName);
                            }
                        }
                    });
                }
                // deletion of todo
                delete(id) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete') }}",
                        data: {_method:"delete", id:id},
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            console.log(response);
                            if(response.status == 200) {
                                $("#myList").html("");
                                for(let x = 0; x < response.list.length; x++) {
                                    const formattedTime = changeTimeFormat(response.list[x].updated_at);
                                    $("#myList").append(
                                        `<tr>
                                            <td>
                                                ${response.list[x].todoName}
                                            </td>
                                            <td>
                                                ${formattedTime}
                                            </td>
                                            <td>
                                                <form class="w-full flex gap-2">
                                                    <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                                    <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                                </form>
                                            </td>
                                        </tr>`
                                    );
                                }
                                Toastify({
                                    text: "Delete success",
                                    className: "info",
                                    style: {
                                        background: "#22c55e",
                                    }
                                }).showToast();
                            } else {
                                Toastify({
                                    text: "Delete failed",
                                    className: "info",
                                    style: {
                                        background: "#ef4444",
                                    }
                                }).showToast();
                            }
                        }
                    });
                }
                // get edit
                getEdit(id) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('getEdit') }}",
                        data: { id:id },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            console.log(response)
                            if(response.status == 200) {
                                $("#modalEdit").addClass("flex").removeClass("hidden");
                                $("#modalEdit input[name=todoName]").val(response.list[0].todoName);
                                $("#modalEdit input[name=todoId]").val(response.list[0].id);
                            } else {
                                Toastify({
                                    text: "Failed",
                                    className: "info",
                                    style: {
                                        background: "#ef4444",
                                    }
                                }).showToast();
                            }
                        }
                    });
                }
                // save changes
                saveChanges(id, todoName) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('save') }}",
                        data: {_method:"put", id:id, todoName:todoName},
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            console.log(response);
                            if(response.status == 200) {
                                $("#modalEdit").addClass("hidden").removeClass("flex");
                                $("#modalEdit input[name=todoName]").val("");
                                $("#modalEdit input[name=todoId]").val("");
                                $("#myList").html("");
                                for(let x = 0; x < response.list.length; x++) {
                                    const formattedTime = changeTimeFormat(response.list[x].updated_at);
                                    $("#myList").append(
                                        `<tr>
                                            <td>
                                                ${response.list[x].todoName}
                                            </td>
                                            <td>
                                                ${formattedTime}
                                            </td>
                                            <td>
                                                <form class="w-full flex gap-2">
                                                    <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-info btn-active edit">edit</button>
                                                    <button type="submit" value="${response.list[x].id}" class="btn btn-xs btn-error btn-active delete">delete</button>
                                                </form>
                                            </td>
                                        </tr>`
                                    );
                                }
                                Toastify({
                                    text: "Update success",
                                    className: "info",
                                    style: {
                                        background: "#22c55e",
                                    }
                                }).showToast();
                            } else {
                                Toastify({
                                    text: "Edit failed",
                                    className: "info",
                                    style: {
                                        background: "#ef4444",
                                    }
                                }).showToast();
                            }
                        }
                    });
                }
            }
            // instanciation of class request
            const request = new Request();
            let myList = document.querySelector("#myList");
            myList.addEventListener('click', (event) => {
                event.preventDefault();
                const id = event.target.value;
                if (event.target.classList.contains("delete")) {
                    request.delete(id);
                }
                if (event.target.classList.contains("edit")) {
                    request.getEdit(id);
                }
            });
            // add list
            $("#addList").click(function (e) { 
                e.preventDefault();
                var todoName = $("#modalAdd input[name=todoName]").val();
                request.add(todoName)
            });
            // save changes
            $("#editSave").click(function (e) { 
                e.preventDefault();
                var id = $("#modalEdit input[name=todoId]").val();
                var todoName = $("#modalEdit input[name=todoName]").val();
                request.saveChanges(id, todoName);
            });
            // chang time format
            function changeTimeFormat(time) {
                const timestampString = time;
                const timestamp = new Date(timestampString);
                const formattedTimestamp = timestamp.toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: 'numeric',
                    hour12: true
                });
                return formattedTimestamp;
            }
            // show modal add
            $("#showModalAdd").click(function () { 
                $("#modalAdd").addClass("flex").removeClass("hidden");
            });
            // hide modal add
            $("#hideModalAdd").click(function () { 
                $("#modalAdd").addClass("hidden").removeClass("flex");
            });
            // hide modal edit
            $("#hideModalEdit").click(function () { 
                $("#modalEdit").addClass("hidden").removeClass("flex");
                $("#modalEdit input[name=todoName]").val("");
                $("#modalEdit input[name=todoId]").val("");
            });
        });
    </script>
</body>
</html>