Perfect! Let’s build the **nested submenu version** step by step, just like a real e-commerce admin sidebar:

* **Parent menu** = Category (Products, Sales, Users…)
* **Child menu** = Permissions (List, Create, Import…)
* **Collapsible nested menus**
* **Active highlight** on current page
* Fully **dynamic from permissions table**, no hardcoding

---

# **STEP 1 — Update Permissions Table for Nested Menus**

Add a `parent` column to indicate if a permission is a **submenu**:

```sql
ALTER TABLE permissions
ADD COLUMN parent VARCHAR(100) DEFAULT NULL;
```

**Example Data:**

| name            | label          | category  | icon           | parent   |
| --------------- | -------------- | --------- | -------------- | -------- |
| view_dashboard  | Dashboard      | Dashboard | bi-speedometer | NULL     |
| view_products   | Products List  | Products  | bi-box-seam    | Products |
| create_products | Create Product | Products  | bi-plus-circle | Products |
| import_products | Import Product | Products  | bi-upload      | Products |
| view_sales      | Sales List     | Sales     | bi-shop        | Sales    |
| create_sales    | Create Sale    | Sales     | bi-plus-circle | Sales    |

> `parent = category` links child menu to its parent.

---

# **STEP 2 — User Model**

```php
public function permissions()
{
    return \DB::table('permissions')
        ->join('group_permissions','permissions.id','=','group_permissions.permission_id')
        ->where('group_permissions.group_id',$this->group_id)
        ->select('permissions.*')
        ->get();
}
```

---

# **STEP 3 — Blade Sidebar (Nested Submenus)**

```blade
@php
$categories = $userPermissions->whereNull('parent')->groupBy('category');
@endphp

<div class="p-3 bg-light" style="min-height:100vh;">

    @foreach($categories as $category => $parents)
        @php
            $collapseId = 'nav-'.Str::slug($category);
            $children = $userPermissions->where('parent', $category);
            $isActive = $children->contains(fn($p) => request()->is('*'.$p->name.'*'));
        @endphp

        {{-- Parent Menu --}}
        <a class="nav-link d-flex align-items-center justify-content-between mb-1
            {{ $isActive ? 'active parent-active' : '' }}"
           data-bs-toggle="collapse"
           href="#{{ $collapseId }}"
           aria-expanded="{{ $isActive ? 'true' : 'false' }}">
            <div>
                <i class="bi {{ $parents->first()->icon }} me-2"></i>
                <span>{{ $category }}</span>
            </div>
            <i class="bi bi-chevron-down"></i>
        </a>

        {{-- Nested Submenu --}}
        <div id="{{ $collapseId }}" class="collapse {{ $isActive ? 'show' : '' }} ps-4 mb-2">
            @foreach($children as $child)
                <a href="{{ url($prefix.'/'.str_replace('_','/',$child->name)) }}"
                   class="nav-link d-flex align-items-center {{ request()->is('*'.$child->name.'*') ? 'active' : '' }}">
                    <i class="bi {{ $child->icon }} me-2"></i>
                    <span>{{ $child->label }}</span>
                </a>
            @endforeach
        </div>

    @endforeach

</div>
```

---

# **STEP 4 — How It Works**

1. **Parent menu** = category (e.g., Products)
2. **Child menus** = permissions under that category (List / Create / Import)
3. Collapse opens automatically if **any child menu is active**
4. Users only see **menus they have permission for**

---

# **STEP 5 — Example Layout**

```
Dashboard
Products ▼
   • Products List
   • Create Product
   • Import Product
Sales ▼
   • Sales List
   • Create Sale
Users ▼
   • Users
Settings ▼
   • System Settings
Reports ▼
   • Reports
Shop ▼
   • Shop Settings
```

* Admin sees all menus
* Saler sees **Products + Sales + Reports**
* User sees only **Dashboard + Products List**

---

# ✅ Optional Enhancements

* Multi-level nested menus (sub-submenus)
* Badge counters (pending orders, notifications)
* AJAX load/save for permissions
* Highlight parent category if any child is active

---

If you want, I can now **create a live demo with working collapsible sidebar, Bootstrap icons, and pre-filled permissions** so you can **copy-paste directly into your Laravel project**.

Do you want me to do that next?
