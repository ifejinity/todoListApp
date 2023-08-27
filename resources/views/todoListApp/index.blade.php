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
                                    <div class="w-full flex gap-2">
                                        <a href="" class="btn btn-xs">edit</a>
                                        <a href="" class="btn btn-xs">delete</a>
                                    </div>
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
            <input type="text" name="todoName" class="input input-bordered w-full mb-3" placeholder="Todo name">
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
                                        <div class="w-full flex gap-2">
                                            <a href="" class="btn btn-xs">edit</a>
                                            <a href="" class="btn btn-xs">delete</a>
                                        </div>
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
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>