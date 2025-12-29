# IMEI/Serial Number Tracking Module - Implementation Plan

## Overview
এই module টি products এর individual serial number (IMEI, Serial No, etc.) track করার জন্য। এটা enable করলে purchase এ multiple IMEI input করা যাবে, stock এ individual IMEI track হবে, এবং sell এর সময় specific IMEI select করতে হবে।

---

## Phase 1: Database Setup

### 1.1 New Migration: Create `product_serials` Table
```
Schema:
- id (primary key)
- business_id (foreign key -> business.id)
- product_id (foreign key -> products.id)
- variation_id (foreign key -> variations.id)
- location_id (foreign key -> business_locations.id)
- serial_number (string, unique per business) - IMEI/Serial
- purchase_line_id (foreign key -> purchase_lines.id, nullable)
- sell_line_id (foreign key -> transaction_sell_lines.id, nullable) - NULL means in stock
- status (enum: 'available', 'sold', 'returned', 'damaged')
- purchase_date (datetime)
- sold_date (datetime, nullable)
- created_at
- updated_at
```

### 1.2 Modify `business` Table
Add column:
- `enable_imei_tracking` (boolean, default: 0)

### 1.3 Modify `products` Table
Add column:
- `enable_imei_for_product` (boolean, default: 0) - Per product level control

---

## Phase 2: Settings & Configuration

### 2.1 Business Settings Page
**File:** `resources/views/business/settings.blade.php`

Add new section "IMEI/Serial Tracking":
```
[ ] Enable IMEI/Serial Number Tracking
    - When enabled, shows IMEI input options in Purchase
    - When enabled, requires IMEI selection in Sell for applicable products
```

### 2.2 Product Settings
**File:** `resources/views/product/create.blade.php` & `edit.blade.php`

Add checkbox (visible only if business IMEI tracking enabled):
```
[ ] Enable IMEI tracking for this product
```

---

## Phase 3: Purchase Module Changes

### 3.1 Purchase Form UI
**File:** `resources/views/purchase/create.blade.php`

For products with IMEI enabled:
- Replace quantity input with IMEI input area
- Allow multiple IMEI entry (one per line or comma separated)
- Auto-calculate quantity from IMEI count
- Validate: No duplicate IMEI in system

**UI Design:**
```
+------------------------------------------+
| Product: iPhone 16                        |
| +--------------------------------------+ |
| | Enter IMEI Numbers (one per line):   | |
| | 123456789012345                       | |
| | 123456789012346                       | |
| | 123456789012347                       | |
| +--------------------------------------+ |
| Quantity: 3 (auto-calculated)            |
+------------------------------------------+
```

### 3.2 Purchase Controller
**File:** `app/Http/Controllers/PurchaseController.php`

Modify `store()` method:
- After saving purchase line, create entries in `product_serials` table
- Each IMEI = 1 row with status 'available'

### 3.3 IMEI Validation
- Check duplicate within same input
- Check duplicate in database (same business)
- Minimum/Maximum length validation (configurable)

---

## Phase 4: Stock Management

### 4.1 Stock Report Enhancement
**File:** `resources/views/report/stock_report.blade.php`

Add "View Serials" button for IMEI-enabled products:
- Shows list of all serial numbers
- Filter by: Available, Sold, All
- Search by serial number

### 4.2 New Page: Serial Number Management
**Route:** `/products/serials`
**Features:**
- List all serial numbers with filters
- Search by serial number
- View history (purchase date, sold date, customer)
- Export to Excel/CSV

---

## Phase 5: Sell/POS Module Changes

### 5.1 POS Screen UI
**File:** `resources/views/sale_pos/create.blade.php`

For IMEI-enabled products:
- After adding product, show IMEI selection modal
- Options to select IMEI:
  1. Dropdown list of available IMEIs
  2. Barcode scanner input
  3. Search/type IMEI manually

**UI Design:**
```
+------------------------------------------+
| Select IMEI for: iPhone 16               |
| +--------------------------------------+ |
| | [Scan Barcode] or [Search IMEI]      | |
| +--------------------------------------+ |
| Available IMEIs:                         |
| ( ) 123456789012345                      |
| ( ) 123456789012346                      |
| ( ) 123456789012347                      |
| [Select] [Cancel]                        |
+------------------------------------------+
```

### 5.2 Barcode Scanner Support
- Auto-focus on IMEI input field
- On scan, auto-select matching IMEI
- If not found, show error

### 5.3 Sell Controller
**File:** `app/Http/Controllers/SellPosController.php`

Modify `store()` method:
- Update `product_serials` table:
  - Set `sell_line_id`
  - Set `status` = 'sold'
  - Set `sold_date`

### 5.4 Validation
- Cannot sell without selecting IMEI (for IMEI-enabled products)
- Cannot select already sold IMEI
- Quantity must match selected IMEI count

---

## Phase 6: Invoice Changes

### 6.1 Invoice Template
**File:** `resources/views/sale_pos/receipts/pacific.blade.php` (and others)

Add IMEI display in product line:
```
| Product          | Qty | Price  | Total  |
|------------------|-----|--------|--------|
| iPhone 16        | 1   | 125000 | 125000 |
| IMEI: 123456789012345                    |
```

### 6.2 Receipt Details
**File:** `app/Utils/TransactionUtil.php`

Modify `getReceiptDetails()`:
- Include serial numbers in line items
- Format: Single IMEI per line or comma-separated

---

## Phase 7: Returns Handling

### 7.1 Sell Return
When a sale is returned:
- Update `product_serials`:
  - Clear `sell_line_id`
  - Set `status` = 'available' (or 'returned' for tracking)
  - Clear `sold_date`

### 7.2 Purchase Return
When purchase is returned:
- Update `product_serials`:
  - Set `status` = 'returned'
  - Or delete the record

---

## Phase 8: Reports & Analytics

### 8.1 Serial Number Report
- All serials with current status
- Filter by product, status, date range
- Export functionality

### 8.2 Serial History Report
- Track lifecycle of each serial
- Purchase date, sold date, customer info
- Return history if any

---

## Implementation Order

### Step 1: Database (Day 1)
1. Create migration for `product_serials` table
2. Add `enable_imei_tracking` to `business` table
3. Add `enable_imei_for_product` to `products` table
4. Run migrations

### Step 2: Settings (Day 1)
1. Add IMEI toggle in Business Settings
2. Add IMEI checkbox in Product form
3. Create Model for ProductSerial

### Step 3: Purchase (Day 2-3)
1. Modify purchase form UI
2. Add IMEI input component
3. Modify PurchaseController to save serials
4. Add validation

### Step 4: Stock (Day 3)
1. Create Serial Management page
2. Add "View Serials" in stock report
3. Search and filter functionality

### Step 5: Sell/POS (Day 4-5)
1. Add IMEI selection modal in POS
2. Barcode scanner integration
3. Modify SellPosController
4. Validation for IMEI selection

### Step 6: Invoice (Day 5)
1. Modify invoice templates
2. Display IMEI in receipt

### Step 7: Returns (Day 6)
1. Handle sell return
2. Handle purchase return

### Step 8: Reports (Day 6-7)
1. Serial number report
2. Export functionality

---

## Files to Create/Modify

### New Files:
```
app/Models/ProductSerial.php
database/migrations/xxxx_create_product_serials_table.php
database/migrations/xxxx_add_imei_tracking_to_business.php
database/migrations/xxxx_add_imei_to_products.php
resources/views/product/partials/imei_input.blade.php
resources/views/sale_pos/partials/imei_selection_modal.blade.php
resources/views/report/serial_report.blade.php
app/Http/Controllers/ProductSerialController.php
```

### Modify Files:
```
app/Business.php - Add imei tracking field
app/Product.php - Add imei field, relationship
app/Http/Controllers/BusinessSettingsController.php
app/Http/Controllers/ProductController.php
app/Http/Controllers/PurchaseController.php
app/Http/Controllers/SellPosController.php
app/Http/Controllers/SellReturnController.php
app/Utils/TransactionUtil.php
resources/views/business/settings.blade.php
resources/views/product/create.blade.php
resources/views/product/edit.blade.php
resources/views/purchase/create.blade.php
resources/views/sale_pos/create.blade.php
resources/views/sale_pos/receipts/*.blade.php
routes/web.php
```

---

## API Endpoints (New)

```
GET  /api/products/{id}/available-serials - Get available serials for a product
POST /api/serials/validate - Validate IMEI before saving
GET  /api/serials/search?q={query} - Search serial number
GET  /api/serials/{serial}/history - Get serial history
```

---

## Testing Checklist

### Purchase:
- [ ] Can add multiple IMEIs in purchase
- [ ] Duplicate IMEI validation works
- [ ] Quantity auto-calculates from IMEI count
- [ ] Serials saved correctly in database

### Stock:
- [ ] Serials show in stock report
- [ ] Filter and search works
- [ ] Export works

### Sell:
- [ ] IMEI selection modal appears
- [ ] Barcode scanner works
- [ ] Cannot sell without IMEI selection
- [ ] Sold serials marked correctly

### Invoice:
- [ ] IMEI prints on invoice
- [ ] Format is correct

### Returns:
- [ ] Returned items become available again
- [ ] History is preserved

---

## Notes

1. **Performance:** Index `serial_number` column for fast search
2. **Security:** Validate business_id in all queries
3. **UX:** Auto-focus on barcode input for faster scanning
4. **Backup:** Serial data is critical, ensure proper backup

---

## Approval

- [ ] Plan reviewed
- [ ] Database design approved
- [ ] UI mockups approved
- [ ] Ready to start implementation
