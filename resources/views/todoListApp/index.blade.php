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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="myList">
                        @foreach ($myList as $item)
                            <tr>
                                <td>{{ $item['todoName'] }}</td>
                                <td>
                                    <form class="w-full flex gap-2">
                                        <button type="submit" id="edit" value="{{ $item['id'] }}" class="btn btn-xs">edit</button>
                                        <button type="submit" id="delete" value="{{ $item['id'] }}" class="btn btn-xs">delete</button>
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
    {{-- script --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function () {
            // show modal add
            $("#showModalAdd").click(function () { 
                $("#modalAdd").addClass("flex").removeClass("hidden");
            });
            // hide modal add
            $("#hideModalAdd").click(function () { 
                $("#modalAdd").addClass("hidden").removeClass("flex");
            });
            // add list
            $("#addList").click(function (e) { 
                e.preventDefault();
                var todoName = $("input[name=todoName]").val();
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
                            $("input[name=todoName]").val("");
                            Toastify({
                                text: "Success",
                                className: "info",
                                style: {
                                    background: "#22c55e",
                                }
                            }).showToast();
                            $("#myList").append(
                                `<tr>
                                    <td>
                                        <div class="badge badge-success">New</div>
                                        ${response.list.todoName}
                                    </td>
                                    <td>
                                        <form class="w-full flex gap-2">
                                            <button type="submit" id="edit" value="${response.list.id}" class="btn btn-xs">edit</button>
                                            <button type="submit" id="delete" value="${response.list.id}" class="btn btn-xs">delete</button>
                                        </form>
                                    </td>
                                </tr>`
                            );
                        } else {
                            Toastify({
                                text: "Failed",
                                className: "info",
                                style: {
                                    background: "#ef4444",
                                }
                            }).showToast();
                            $("#errorTodoName").html(response.errorMessages.todoName);
                        }
                    }
                });
            });

            $("#delete").click(function (e) { 
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete') }}",
                    data: {_method:"delete", id:$(this).val()},
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
</body>
</html>