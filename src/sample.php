<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample unix code runner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1>commmand runner</h1>
                    </div>
                    <div class="card-body">
                        <form id="formdata">
                            <div class="form-group">
                                <label for=""></label>
                                <input type="text" class="form-control" name="cmd" id="cmd" >
                            </div>
                            <button type="submit" id="sub-btn" class="btn btn-success">Run</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Trackers</h2>
                    </div>
                    <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ran By</th>
                                <th scope="col">Command</th>
                                <th scope="col">Started At</th>
                                <th scope="col">Status</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dataRow">

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="contentViewDetal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Output #ID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #000">
                <code>
                <p  id="detailContent"></p>
                </code>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
        let sb =document.querySelector('#sub-btn');
        let form = document.querySelector('#formdata');
        if (sb) {
            sb.addEventListener('click', function(e) {
                e.preventDefault();
                let cmdValue = document.querySelector('#cmd').value;
                const data = { cmd: cmdValue };
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data) 
                    window.location.reload();
                })
                .catch(error => console.error(error));
            });
        }


        fetch('ManageSample.php').then(response => response.json())
        .then(data =>  {
            let rowTxt = `
                 <tr>
                    <td colspan="6" style="text-align:center">No Data Found</td>
                </tr>
            `;
            if (data.data.length > 0) {
                rowTxt = ``;
                data.data.forEach(element => {
                    rowTxt += `
                        <tr>
                            <td style="text-align:center">${element.id}</td>
                            <td style="text-align:center">John Due</td>
                            <td style="text-align:center; background: #000; color: #fff">${element.command_name}</td>
                            <td style="text-align:center">${element.started_at}</td>
                            <td style="text-align:center">${element.status}</td>
                            <td style="text-align:center">${element.updated_at}</td>
                            <td style="text-align:center">
                                <a href="javascript:void(0)" onclick="contentViewDetal(${element.id})">Output</a>
                                <a href="">kill</a>
                            </td>
                        </tr>
                    `;
                });
            }
            document.querySelector('#dataRow').innerHTML =rowTxt;
        })
        .catch(err => console.error(err));


        function contentViewDetal(id) {
            let detail = document.querySelector('#detailContent');
            detail.innerHTML = '';
            let myModal = new bootstrap.Modal(document.getElementById('contentViewDetal'), {
                keyboard: false
            })
            myModal.show();
            let eventSource;

            if (eventSource) {
                eventSource.close();
            }

            eventSource = new EventSource(`LogoutputSSE.php?id=${id}`);

            eventSource.onmessage = (event) => {
                console.log(event);
                
                
                const message = document.createElement('p');
                message.textContent = event.data;
                detail.appendChild(message);

                if (event.data === 'stream ended') {
                    eventSource.close();
                    console.log('Streaming ended and connection closed');
                }
            }


            eventSource.onerror = err => {
                console.log(err, 'error occurred');
            }

            //normal way of content
        //     fetch(`Logoutput.php?id=${id}`)
        //    .then(response => response.text())
        //    .then(data => {
        //         let myModal = new bootstrap.Modal(document.getElementById('contentViewDetal'), {
        //             keyboard: false
        //         })
        //         myModal.show();
        //         let detail = document.querySelector('#detailContent');
        //         detail.innerHTML = data;
        //    })
        //    .catch(err => console.error(err));
        }
   </script>
</body>
</html>