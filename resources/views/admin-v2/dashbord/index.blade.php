@extends('admin-v2.app')
@section('title', __('messages.dashboard'))

@section('content')
    <!-- STAT STRIP -->
    <div class="stat-strip fade-up">
      <div class="stat-mini">
        <div class="stat-mini-icon">◑</div>
        <div>
          <div class="stat-mini-label">Total Brands</div>
          <div class="stat-mini-value" id="statTotal">0</div>
        </div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-icon">◉</div>
        <div>
          <div class="stat-mini-label">Active</div>
          <div class="stat-mini-value" id="statActive">0</div>
        </div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-icon">◌</div>
        <div>
          <div class="stat-mini-label">Inactive</div>
          <div class="stat-mini-value" id="statInactive">0</div>
        </div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-icon">◈</div>
        <div>
          <div class="stat-mini-label">Categories</div>
          <div class="stat-mini-value" id="statCats">0</div>
        </div>
      </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card fade-up d1">
      <div class="table-card-header">
        <div>
          <div class="table-card-title">All Brands</div>
          <div class="table-card-sub">Manage, update and remove brand records</div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <select class="btn-theme" id="filterStatus" onchange="filterTable()" style="cursor:pointer;padding:7px 12px;">
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
          <select class="btn-theme" id="filterCat" onchange="filterTable()" style="cursor:pointer;padding:7px 12px;">
            <option value="">All Categories</option>
          </select>
        </div>
      </div>
      <div class="table-card-body">
        <table id="brandsTable" class="display nowrap" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Brand</th>
              <th>Category</th>
              <th>Country</th>
              <th>Products</th>
              <th>Status</th>
              <th>Added</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>

  </div><!-- /page-content -->
</div><!-- /main -->

<!-- ══════ ADD / EDIT MODAL ══════ -->
<div class="modal-backdrop-custom" id="formModal" onclick="handleFormBackdrop(event)">
  <div class="custom-modal">
    <div class="modal-hd">
      <div>
        <div class="modal-hd-title" id="formModalTitle">Add New Brand</div>
        <div class="modal-hd-sub"  id="formModalSub">Fill in the brand details below</div>
      </div>
      <button class="modal-close" onclick="closeFormModal()">✕</button>
    </div>

    <div class="modal-body-pad">

      <!-- Emoji / logo picker -->
      <div class="form-row">
        <label class="form-label-custom">Brand Icon</label>
        <div class="emoji-picker" id="emojiPicker"></div>
        <input type="hidden" id="fEmoji" value="◑" />
      </div>

      <div class="form-grid">
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Brand Name *</label>
          <input type="text" class="form-control-custom" id="fName" placeholder="e.g. Apple" />
        </div>
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Slug</label>
          <input type="text" class="form-control-custom" id="fSlug" placeholder="auto-generated" />
        </div>
      </div>

      <div class="form-grid" style="margin-top:16px">
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Category *</label>
          <select class="form-control-custom" id="fCategory">
            <option value="">Select category</option>
            <option>Electronics</option>
            <option>Fashion</option>
            <option>Food & Beverage</option>
            <option>Health & Beauty</option>
            <option>Home & Living</option>
            <option>Sports</option>
            <option>Automotive</option>
            <option>Other</option>
          </select>
        </div>
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Country</label>
          <input type="text" class="form-control-custom" id="fCountry" placeholder="e.g. USA" />
        </div>
      </div>

      <div class="form-grid" style="margin-top:16px">
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Website</label>
          <input type="text" class="form-control-custom" id="fWebsite" placeholder="https://" />
        </div>
        <div class="form-row" style="margin-bottom:0">
          <label class="form-label-custom">Status</label>
          <select class="form-control-custom" id="fStatus">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div class="form-row" style="margin-top:16px">
        <label class="form-label-custom">Description</label>
        <textarea class="form-control-custom" id="fDesc" placeholder="Short brand description…"></textarea>
      </div>

    </div>

    <div class="modal-ft">
      <button class="btn-modal-cancel" onclick="closeFormModal()">Cancel</button>
      <button class="btn-modal-save"   onclick="saveBrand()">Save Brand</button>
    </div>
  </div>
</div>

<!-- ══════ DELETE MODAL ══════ -->
<div class="modal-backdrop-custom" id="deleteModal" onclick="handleDeleteBackdrop(event)">
  <div class="custom-modal delete-modal">
    <div class="delete-icon-wrap">
      <div class="delete-icon">🗑</div>
      <div class="delete-title">Delete Brand</div>
      <div class="delete-sub">
        Are you sure you want to delete
        <span class="delete-brand-name" id="deleteBrandName">"Brand Name"</span>?
        <br>This action cannot be undone.
      </div>
    </div>
    <div class="modal-ft" style="padding-top:8px">
      <button class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
      <button class="btn-modal-delete" onclick="confirmDelete()">Delete Brand</button>
    </div>
  </div>
</div>

<!-- TOAST -->

@endsection
