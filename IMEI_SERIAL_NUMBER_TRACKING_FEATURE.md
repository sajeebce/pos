# IMEI/Serial Number Tracking Feature

## Overview
এই feature টি POS system এ individual product এর IMEI বা Serial Number track করার জন্য ব্যবহৃত হয়। মূলত mobile phone, laptop, electronics ইত্যাদি products এর জন্য যেখানে প্রতিটি unit এর unique serial number থাকে।

---

## Feature Activation

### Step 1: Business Settings এ Enable করা
1. **Settings** > **Business Settings** এ যান
2. **Product** tab এ click করুন
3. **"Enable IMEI/Serial Number Tracking"** checkbox টি check করুন
4. **Update Settings** button এ click করুন

> **Note:** এই setting enable করার পর, Products > Add/Edit Product page এ একটি নতুন option দেখা যাবে।

---

## How It Works

### 1. Purchase এ IMEI Input (Stock In) 🆕 Enhanced
যখন আপনি **Purchases > Add Purchase** (`/purchases/create`) থেকে কোনো product purchase করবেন:

#### IMEI Checkbox System (New)
- Product add করার পর, product এর নামের নিচে **"📦 Enable IMEI for this purchase"** checkbox দেখাবে
- **Checkbox UNCHECKED থাকলে:**
  - Normal purchase হবে, কোনো IMEI input দরকার নেই
  - Quantity যা দেবেন তাই accept হবে
- **Checkbox CHECKED করলে:**
  - IMEI input textarea দেখা যাবে
  - **Quantity = IMEI Count** (mandatory)
  - যত quantity দিবেন, তত IMEI must দিতে হবে
  - IMEI count match না করলে save হবে না, warning দেখাবে

#### IMEI Input Format
**Supported Formats:**
```
Format 1 (One per line):
359123456789012
359123456789013
359123456789014

Format 2 (Comma separated):
359123456789012, 359123456789013, 359123456789014

Format 3 (Mixed):
359123456789012, 359123456789013
359123456789014
359123456789015, 359123456789016
```

**Input Rules:**
- প্রতি লাইনে একটি করে IMEI/Serial Number **অথবা**
- Comma (`,`) দিয়ে আলাদা করে একই লাইনে একাধিক IMEI
- Mixed format ও support করে (কিছু comma-separated, কিছু new line)
- Extra spaces automatically trim হয়ে যাবে
- Empty lines ignore হবে

**Real-time Counter:**
- নিচে real-time count দেখাবে: `"3 IMEI/Serial Number"`
- Textarea তে type করার সাথে সাথে count update হবে

#### Validation Rules
- ⚠️ **Quantity ≠ IMEI Count** → "IMEI count must match quantity" error
- ⚠️ **Duplicate IMEI** → "IMEI already exists in system" error
- ✅ Save করলে serial numbers **"available"** status এ store হবে

### 2. Sale/POS এ IMEI Selection (Stock Out) 🆕 Enhanced
যখন আপনি **Sell > POS** (`/pos/create`) বা **Sell > Add Sale** (`/sells/create`) থেকে product sell করবেন:

#### Multi-Select IMEI Dropdown (New)
- IMEI-attached product add করলে **Multi-select dropdown** দেখা যাবে
- Dropdown features:
  - 🔍 **Search bar** - IMEI search করতে পারবেন
  - 📜 **Lazy loading** - প্রথমে 50টি load হবে, scroll করলে আরো fetch হবে
  - ✅ **Multiple selection** - একসাথে একাধিক IMEI select করতে পারবেন
  - 🔢 **Selected count** - কতটি select করা হয়েছে দেখাবে
- **Quantity = Selected IMEI Count** (auto-sync)
- Sale complete হলে selected serial numbers **"sold"** status এ mark হবে

#### Performance Optimization
```
Initial Load: 50 items
On Scroll: +50 items (infinite scroll)
Search: Server-side filtering
```

### 3. Invoice এ IMEI Display
- Sale এর invoice/receipt এ product এর নিচে **IMEI:** number দেখাবে
- Multiple IMEI থাকলে comma-separated দেখাবে
- Customer কে দেওয়া invoice এ product এর serial number সহ details থাকবে

### 4. Stock History/Report 🆕 Enhanced
**URL:** `/products/stock-history/{product_id}`

#### Current Stock Section
- Product quantity এর পাশে **IMEI Dropdown** দেখাবে
- Available IMEIs list করা থাকবে
- Dropdown features:
  - 🔍 **Search bar** - IMEI search করতে পারবেন
  - 📜 **Lazy loading** - প্রথমে 50টি load হবে, scroll করলে আরো fetch হবে


#### Sold Stock Section
- Sold quantity এর পাশে **Sold IMEIs Dropdown** দেখাবে
- কোন customer কে কোন IMEI sell হয়েছে তা দেখাবে
- Invoice reference সহ details থাকবে

---

## Important Rules

### Setting Disable করার নিয়ম

> **Critical:** একবার কোনো product এ IMEI tracking use করা হলে, Business Settings থেকে "Enable IMEI/Serial Number Tracking" option **uncheck করা যাবে না**।

**কারণ:**
- যদি আপনি setting disable করেন, তাহলে:
  - আগের সব serial number data orphan হয়ে যাবে
  - Stock tracking ভুল হয়ে যাবে
  - Sale history inconsistent হবে

**Behavior:**
- যদি কোনো product এ IMEI tracking enabled থাকে এবং serials exist করে:
  - Checkbox **disabled** অবস্থায় থাকবে
  - Message দেখাবে: *"Some products are using this setting already"*
  - আপনি এটি uncheck করতে পারবেন না

---

## Product Level IMEI Setting

### Enable IMEI for Individual Product
1. **Products** > **Add Product** বা **Edit Product** এ যান
2. যদি Business Settings এ IMEI tracking enabled থাকে, তাহলে দেখবেন:
   - **"Enable IMEI Tracking for this product"** checkbox
3. যে সব products এর serial number track করতে চান শুধু সেগুলোতে check করুন
4. Save করুন

> **Example:** আপনার shop এ iPhone এবং Charger দুটোই আছে। iPhone এর জন্য IMEI tracking enable করবেন, কিন্তু Charger এর জন্য করবেন না।

---

## Database Structure

### product_serials Table
| Column | Description |
|--------|-------------|
| id | Primary key |
| business_id | Business reference |
| product_id | Product reference |
| variation_id | Variation reference |
| location_id | Stock location |
| serial_number | IMEI/Serial number |
| purchase_line_id | Purchase reference |
| sell_line_id | Sale reference (NULL if available) |
| status | available / sold / returned / damaged |
| purchase_date | When purchased |
| sold_date | When sold |

---

## Serial Number Status Flow

```
[Purchase Entry]
      |
      v
  "available"  ----[Sale]----> "sold"
      |                           |
      |                           v
      |                      [Return]
      |                           |
      v                           v
  "damaged"                  "returned"
```

---

## Validation Rules

1. **Duplicate Check:** একই business এ same serial number দুইবার add করা যাবে না
2. **Quantity Match:** Purchase quantity এবং IMEI count match করা উচিত (warning দেখাবে)
3. **Available Check:** শুধুমাত্র "available" status এর serial numbers sell করা যাবে
4. **Location Based:** Serial numbers location-wise track হয়, একই serial বিভিন্ন location এ transfer করা যায়

---

## Pages Affected by This Feature

| Page | Change |
|------|--------|
| Business Settings > Product | IMEI tracking toggle option |
| Products > Add/Edit Product | Per-product IMEI enable option |
| Purchases > Add Purchase | IMEI input textarea |
| Purchases > Edit Purchase | IMEI input (for pending purchases) |
| Sell > POS | IMEI selection dropdown |
| Sell > Add Sale | IMEI selection dropdown |
| Invoice/Receipt | IMEI display under product |
| Reports > Stock Report | Serial-wise stock view |

---

---

## 🚀 Implementation Plan (Pending Features)

### Phase 1: Purchase Enhancement 🔴 High Priority

#### 1.1 IMEI Checkbox in Purchase Row
**Files to modify:**
- `resources/views/purchase/partials/purchase_entry_row.blade.php`
- `public/js/purchase.js`

**Implementation:**
```
┌─────────────────────────────────────────────────────────┐
│ iPhone 16 Pro Max                                        │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ ☐ Enable IMEI for this purchase                     │ │
│ └─────────────────────────────────────────────────────┘ │
│                                                         │
│ [Hidden by default - shows when checkbox checked]       │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ IMEI/Serial Numbers:                                │ │
│ │ ┌─────────────────────────────────────────────────┐ │ │
│ │ │ 359123456789012                                 │ │ │
│ │ │ 359123456789013                                 │ │ │
│ │ └─────────────────────────────────────────────────┘ │ │
│ │ ✓ 2 IMEI/Serial Number                             │ │
│ └─────────────────────────────────────────────────────┘ │
│                                                         │
│ Quantity: [2] (auto-synced with IMEI count)             │
└─────────────────────────────────────────────────────────┘
```

**Logic:**
- Checkbox unchecked → Normal purchase, quantity editable
- Checkbox checked → IMEI textarea visible, quantity = IMEI count (read-only)
- Real-time IMEI count update on textarea change
- Validation: Quantity must equal IMEI count on form submit

#### 1.2 Duplicate IMEI Validation
**Files to modify:**
- `app/Utils/ProductUtil.php` → `saveSerialNumbers()`
- `app/Http/Controllers/PurchaseController.php`

**Validation:**
- Check if IMEI already exists in `product_serials` table (same business_id)
- Return error with duplicate IMEI number highlighted
- AJAX validation before form submit (optional enhancement)

---

### Phase 2: POS/Sell Enhancement 🔴 High Priority

#### 2.1 Multi-Select IMEI Dropdown with Search
**Files to modify:**
- `resources/views/sale_pos/product_row.blade.php`
- `app/Http/Controllers/SellPosController.php`
- `public/js/pos.js`

**UI Design:**
```
┌─────────────────────────────────────────────────────────┐
│ iPhone 16 Pro Max                    Qty: [2]           │
│                                                         │
│ Select IMEI: ▼                                          │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ 🔍 Search IMEI...                                   │ │
│ ├─────────────────────────────────────────────────────┤ │
│ │ ☑ 359123456789012                                   │ │
│ │ ☑ 359123456789013                                   │ │
│ │ ☐ 359123456789014                                   │ │
│ │ ☐ 359123456789015                                   │ │
│ │ ... (scroll for more)                               │ │
│ └─────────────────────────────────────────────────────┘ │
│ Selected: 2 | Available: 150                            │
└─────────────────────────────────────────────────────────┘
```

**Features:**
- Multi-select enabled (checkboxes)
- Search bar with server-side filtering
- Lazy loading (50 items initial, load more on scroll)
- Quantity auto-syncs with selected IMEI count
- Selected IMEIs shown as tags/chips

#### 2.2 IMEI Lazy Loading API
**New Route:** `GET /api/serials/available`

**Parameters:**
```
product_id: required
variation_id: required
location_id: required
search: optional (IMEI search term)
page: optional (for pagination)
per_page: 50 (default)
```

**Response:**
```json
{
  "data": [
    {"id": 1, "serial_number": "359123456789012"},
    {"id": 2, "serial_number": "359123456789013"}
  ],
  "total": 150,
  "has_more": true
}
```

---

### Phase 3: Stock Report Enhancement 🟡 Medium Priority

#### 3.1 Stock History Page IMEI Display
**URL:** `/products/stock-history/{product_id}`

**Files to modify:**
- `resources/views/product/stock_history.blade.php`
- `app/Http/Controllers/ProductController.php`

**UI Design:**
```
┌──────────────────────────────────────────────────────────────────┐
│ Stock History: iPhone 16 Pro Max                                  │
├──────────────────────────────────────────────────────────────────┤
│ Location     │ Current Stock │ IMEIs              │ Sold │ IMEIs │
├──────────────┼───────────────┼────────────────────┼──────┼───────┤
│ Main Store   │ 5             │ [View 5 IMEIs ▼]   │ 12   │ [▼]   │
│              │               │ ┌────────────────┐ │      │       │
│              │               │ │ 359123456789012│ │      │       │
│              │               │ │ 359123456789013│ │      │       │
│              │               │ │ 359123456789014│ │      │       │
│              │               │ │ 359123456789015│ │      │       │
│              │               │ │ 359123456789016│ │      │       │
│              │               │ └────────────────┘ │      │       │
├──────────────┼───────────────┼────────────────────┼──────┼───────┤
│ Warehouse    │ 3             │ [View 3 IMEIs ▼]   │ 5    │ [▼]   │
└──────────────┴───────────────┴────────────────────┴──────┴───────┘
```

#### 3.2 Sold IMEIs with Customer Info
**Sold IMEIs Dropdown:**
```
┌────────────────────────────────────────────────┐
│ IMEI: 359123456789001                          │
│ Customer: John Doe                              │
│ Invoice: INV-2025-001                          │
│ Date: 2025-12-20                               │
├────────────────────────────────────────────────┤
│ IMEI: 359123456789002                          │
│ Customer: Jane Smith                           │
│ Invoice: INV-2025-002                          │
│ Date: 2025-12-21                               │
└────────────────────────────────────────────────┘
```

---

### Phase 4: Return Handling 🔴 High Priority

#### 4.1 Sell Return - IMEI Back to Available
**Files to modify:**
- `app/Http/Controllers/SellReturnController.php`
- `app/Utils/TransactionUtil.php`

**Logic:**
- When a sale with IMEI is returned
- Find the `product_serials` record by `sell_line_id`
- Update status: `sold` → `returned` or `available`
- Clear `sell_line_id` and `sold_date`
- IMEI becomes available for new sale

#### 4.2 Purchase Return Handling
**Files to modify:**
- `app/Http/Controllers/PurchaseReturnController.php`

**Logic:**
- When purchase with IMEI is returned to supplier
- Update status: `available` → `returned_to_supplier`
- Or delete the serial record entirely
- Stock count adjusts accordingly

---

### Phase 5: Serial Number Management Page 🟡 Medium Priority

#### 5.1 New Management Page
**Route:** `/products/serials`

**Features:**
- List all serial numbers across all products
- Filters: Product, Status, Location, Date Range
- Search by serial number
- Export to Excel/CSV
- View serial history (timeline)

**UI:**
```
┌──────────────────────────────────────────────────────────────────┐
│ Serial Number Management                          [Export Excel] │
├──────────────────────────────────────────────────────────────────┤
│ Product: [All Products ▼]  Status: [All ▼]  Location: [All ▼]   │
│ Search: [__________________] [Search]                            │
├──────────────────────────────────────────────────────────────────┤
│ Serial Number    │ Product      │ Status    │ Location  │ Action│
├──────────────────┼──────────────┼───────────┼───────────┼───────┤
│ 359123456789012  │ iPhone 16    │ Available │ Main      │ [👁]  │
│ 359123456789013  │ iPhone 16    │ Sold      │ Main      │ [👁]  │
│ 359123456789014  │ Samsung S24  │ Returned  │ Warehouse │ [👁]  │
└──────────────────┴──────────────┴───────────┴───────────┴───────┘
```

---

### Phase 6: Additional Features 🟢 Low Priority

#### 6.1 Barcode Scanner Support
- Auto-focus on IMEI input field in POS
- Scan → Auto-select matching IMEI
- Audio feedback on successful scan

#### 6.2 Bulk IMEI Import
- Excel/CSV upload for bulk IMEI entry in purchase
- Template download option
- Validation report after import

#### 6.3 IMEI Search Across Products
- Global search bar for IMEI
- Shows product, status, customer (if sold)
- Quick links to related transactions

#### 6.4 Serial History Timeline
**Per Serial View:**
```
Timeline:
├─ 2025-12-15: Purchased (Supplier: ABC Electronics, Invoice: PO-001)
├─ 2025-12-18: Sold (Customer: John Doe, Invoice: INV-001)
└─ 2025-12-20: Returned (Reason: Defective, Invoice: RET-001)
```

---

### Phase 7: API Endpoints 🟢 Low Priority

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/serials/available` | Get available serials (paginated, searchable) |
| POST | `/api/serials/validate` | Validate IMEI before saving |
| GET | `/api/serials/search` | Global IMEI search |
| GET | `/api/serials/{id}/history` | Get serial history timeline |
| POST | `/api/serials/bulk-import` | Bulk import from CSV |

---

## 📋 Implementation Checklist

### Purchase Module
- [x] Add IMEI checkbox in purchase row
- [x] Toggle IMEI textarea on checkbox change
- [x] Auto-sync quantity with IMEI count
- [x] Duplicate IMEI validation
- [x] Quantity ≠ IMEI count validation
- [x] Support comma-separated and newline IMEI input

### POS/Sell Module
- [x] Convert single-select to multi-select dropdown
- [x] Add search bar in IMEI dropdown (Select2)
- [x] Auto-sync quantity with selected IMEI count
- [x] Mark multiple serials as sold
- [ ] Implement lazy loading (50 items per page) - Optional

### Stock Report
- [x] Add IMEI dropdown in stock history page
- [x] Show available IMEIs with current stock
- [x] Show sold IMEIs with customer info
- [ ] Export functionality

### Returns
- [x] Handle sell return - IMEI back to available/returned
- [ ] Handle purchase return - IMEI removal

### Management
- [ ] Create serial management page
- [ ] Implement filters and search
- [ ] Add export functionality
- [ ] Serial history timeline view

---

## Troubleshooting

### IMEI field not showing in Purchase page
1. Check Business Settings > Product > "Enable IMEI/Serial Number Tracking" is checked
2. Check the specific product has "Enable IMEI Tracking for this product" checked
3. Logout and Login again (session cache issue)

### Cannot select IMEI in POS/Sale
1. Make sure you have purchased products with IMEI numbers first
2. Check the product location matches sale location
3. Verify serial numbers are in "available" status

### "Serial not available" message
- এর মানে হলো এই product এর জন্য কোনো available serial number নেই
- আগে Purchase করে IMEI add করতে হবে

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2025-12-24 | Initial implementation |
| 1.1 | 2025-12-24 | Added comprehensive implementation plan for pending features |
| 2.0 | 2025-12-24 | Implemented all high priority features: Purchase IMEI checkbox, Multi-select POS dropdown, Quantity sync, Duplicate validation, Stock report IMEI display, Sell return IMEI handling |

---

## 📊 Feature Status Summary

| Feature | Status | Priority |
|---------|--------|----------|
| Business Settings IMEI Toggle | ✅ Done | - |
| Product Level IMEI Enable | ✅ Done | - |
| Purchase IMEI Input Textarea | ✅ Done | - |
| POS Single IMEI Selection | ✅ Done | - |
| Invoice IMEI Display | ✅ Done | - |
| Purchase IMEI Checkbox Toggle | ✅ Done | 🔴 High |
| Quantity = IMEI Count Validation | ✅ Done | 🔴 High |
| Duplicate IMEI Validation | ✅ Done | 🔴 High |
| POS Multi-Select Dropdown | ✅ Done | 🔴 High |
| IMEI Search in Dropdown | ✅ Done | 🔴 High |
| Comma/Newline IMEI Input | ✅ Done | 🔴 High |
| Sell Return IMEI Handling | ✅ Done | 🔴 High |
| Stock Report IMEI Display | ✅ Done | 🟡 Medium |
| Serial Management Page | ⏳ Pending | 🟡 Medium |
| Purchase Return Handling | ⏳ Pending | 🟡 Medium |
| Barcode Scanner Support | ⏳ Pending | 🟢 Low |
| Bulk IMEI Import | ⏳ Pending | 🟢 Low |
| API Endpoints | ⏳ Pending | 🟢 Low |

---

*Document created for NomanPOS - IMEI/Serial Number Tracking Module*
