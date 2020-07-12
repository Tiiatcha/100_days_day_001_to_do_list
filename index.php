<?php include "_head.php"; ?>
    <body>
    <?php include "_nav.php"; ?>
     

    <div class="wrapper">
        <div class="sidebar__container">
            <nav id="sidebar" class="sidebar">
                <div class="sidebar-header">
                    <h3>Side bar</h3>
                </div>
                <ul class="list-unstyle components">
                    <p>The providers</p>
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="#">Link 1</a>
                            </li>
                            <li>
                                <a href="#">Link 2</a>
                            </li>
                        </ul>    
                    </li>
                    <li>
                        <a href="#">Link 3</a>
                    </li>
                    <li><a href="#"></a></li>
                </ul>
            </nav>
        </div>
        <div id="content">
            <div class="container-fluid">
                <div class="row mt-1">
                    <div class="col">
                        <button class="btn btn-info" id="side_menu_btn">
                            Menu
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card" style="width: 20rem;">
                            <div class="card-body">
                                <h5 class="card-title">Craig Davison</h5>
                                <form action="app\handlers\add_item.php" data-options='{}'>
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <input type="text" class="form-control mb-2" name="todo_action" id="new_action" placeholder="What do you want to do?">
                                        </div>
                                        <div class="col-auto">
                                            <button id="add_basic_todo" type="button" class="btn btn-primary mb-2">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Cras justo odio</li>
                                <li class="list-group-item">Dapibus ac facilisis in</li>
                                <li class="list-group-item">Vestibulum at eros</li>
                            </ul>
                        </div>    
                    </div>
                </div>
            </div>
        </div><!-- End "Content" -->
    </div><!-- Wrapper End -->

     
    
    <?php include "_footer.php" ?>
    <script>
        const add_todo = (e)=>{
            form_submit(e.target.form);
        }
        (()=>{
            const add_basic_todo_btn = document.querySelector('#add_basic_todo');
            add_basic_todo_btn.addEventListener('click',add_todo);
        })();
    </script>
    </body>
</html>