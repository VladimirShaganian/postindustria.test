<?php $this->common->get_header(); ?>
<div class="loader"></div>
<header>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">VPN manager</a>
            </div>
        </div>
    </nav>
</header>
<div class="container" id="pageContent">
    <div>
        <ul id="mainTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#companies" aria-controls=companies role="tab" data-toggle="tab" data-type="companies">Companies</a></li>
            <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab" data-type="users">Users</a></li>
            <li role="presentation"><a href="#abuses" aria-controls="abuses" role="tab" data-toggle="tab" data-type="abuses">Abuses</a></li>
        </ul>
        <div class="tab-content">
            <!-- Companies panel -->
            <div role="tabpanel" class="tab-pane active" id="companies">
                <table class="table" id="companiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Quota (TB)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <hr>
                <br>
                <form id="addCompanyForm" class="form">
                    <div class="form-group">
                        <label for="company-name">Name</label>
                        <input type="text" class="form-control" id="company-name" name="name" placeholder="Company name">
                    </div>
                    <div class="form-group">
                        <label for="company-quota">Quota</label>
                        <input type="text" class="form-control" id="company-quota" name="quota" placeholder="Quota size">
                    </div>
                    <button type="submit" class="btn btn-default btn-success">Add</button>
                    <button type="button" id="cancelAddCompany" class="btn btn-default btn-danger">Cancel</button>
                </form>
                <button id="addCompany" class="btn btn-primary">Add company</button>
            </div><!--// Companies panel -->

            <!-- Users panel -->
            <div role="tabpanel" class="tab-pane" id="users">
                <table id="usersTable" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <hr>
                <form id="addUserForm" class="form">
                    <div class="form-group">
                        <label for="user-name">Name</label>
                        <input type="text" class="form-control" id="user-name" name="name" placeholder="User name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="userCompany">Company</label>
                        <select id="userCompany" class="form-control" name="company_id"></select>
                    </div>
                    <button type="submit" class="btn btn-default btn-success">Add</button>
                    <button type="button" id="cancelAddUser" class="btn btn-default btn-danger">Cancel</button>
                </form>
                <button id="addUser" class="btn btn-primary">Add user</button>
            </div><!--// Users panel -->

            <!-- Abuses panel -->
            <div role="tabpanel" class="tab-pane" id="abuses">
                <br>
                <button id="showReport" class="btn btn-success">Show report</button>
                <button id="generateData" class="btn btn-primary">Generate data</button>
                <div class="form-group pull-right">
                    <select id="monthList" class="form-control">
                        <option value="">All time</option>
                        <?php foreach($dates as $date): ?>
                            <option value="<?=$date?>"><?=$date?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <hr>
                <table id="abusesTable" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Summary</th>
                            <th>Quota</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div> <!--// Abuses panel -->
        </div>
    </div>
</div>

<?php $this->common->get_footer(); ?>
