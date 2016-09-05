
<div class="container padding">
  <input type="text" class="form-control padding" id="username" placeholder="update user name"/>
  <button class="btn button padding" onclick="update_user_name()" >UPDATE USER NAME</button>

  <input type="text" class="form-control padding" placeholder="update user password" id="password"/>
  <button class="btn button padding" onclick="update_user_password()">UPDATE PASSWORD</button>
</div>
<div class="modal fade" id="deletemodal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete account ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="delete_account()" >DELETE</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
