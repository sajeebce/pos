# Profit/Loss Report - Unrealized & Realized Profit Calculation Analysis

## Current Implementation vs Cost Recovery Method

### 1. Current Implementation (Existing Code)

**Location:** `app/Utils/TransactionUtil.php` (lines 5933-5939)

**Current Formulas:**
```
Unrealized Profit = Credit Sales Total - Credit Sales COGS - Cash Collected
Realized Profit = Net Profit - Unrealized Profit
```

**Code:**
```php
// Calculate Unrealized Profit
$unrealized_profit = $credit_sales_total - $credit_sales_cogs - $credit_sales_cash_collected;

// Realized Profit (calculated in view)
$realized_profit = $net_profit - $unrealized_profit;
```

---

### 2. Cost Recovery Method (PDF Specification)

**Formulas:**
```
IF (Cash Collected <= Credit Sales COGS):
    Realized Profit = 0
ELSE:
    Realized Profit = Cash Collected - Credit Sales COGS

Unrealized Profit = Total Net Profit - Realized Profit
```

---

## Comparison with Example

**Scenario:**
- Credit Sales Total: ৳150
- Credit Sales COGS: ৳100
- Net Profit: ৳50 (150 - 100)
- Cash Collected: ৳120

### Current Implementation Result:

| Metric | Calculation | Result |
|--------|-------------|--------|
| Unrealized Profit | 150 - 100 - 120 | **-70** |
| Realized Profit | 50 - (-70) | **120** |

**Problem:** Unrealized Profit is NEGATIVE (-70), which doesn't make sense. Realized Profit (120) exceeds Net Profit (50), which is also incorrect.

### Cost Recovery Method Result:

| Metric | Calculation | Result |
|--------|-------------|--------|
| Cash Collected > COGS? | 120 > 100 | **Yes** |
| Realized Profit | 120 - 100 | **20** |
| Unrealized Profit | 50 - 20 | **30** |

**Correct:** Both values are logical and within expected bounds.

---

## Step-by-Step Verification (from PDF)

| Step | Cash Collected | COGS | Condition | Realized Profit | Unrealized Profit |
|------|---------------|------|-----------|-----------------|-------------------|
| 1 | ৳50 | ৳100 | 50 <= 100 | **0** | **50** |
| 2 | ৳100 | ৳100 | 100 <= 100 | **0** | **50** |
| 3 | ৳120 | ৳100 | 120 > 100 | **20** (120-100) | **30** |
| 4 | ৳150 | ৳100 | 150 > 100 | **50** (150-100) | **0** |

**Logic Explanation:**
1. First, recover the cost (COGS)
2. Only after cost is fully recovered, remaining cash becomes profit
3. Unrealized = Profit that hasn't been collected yet

---

## Issues with Current Implementation

1. **Negative Unrealized Profit:** When Cash Collected > (Credit Sales Total - COGS), unrealized profit becomes negative
2. **Realized Profit > Net Profit:** This is mathematically impossible but happens with current formula
3. **Conceptually Wrong:** Current formula doesn't follow accounting principles

---

## Recommendation

### Update Required: YES

The current implementation should be updated to follow the **Cost Recovery Method** as specified in the PDF.

### Proposed Code Changes:

**File:** `app/Utils/TransactionUtil.php`

**Method:** `getCreditSalesProfitData()`

**Replace lines 5933-5939 with:**

```php
// Calculate Net Profit from Credit Sales
$credit_sales_net_profit = $credit_sales_total - $credit_sales_cogs;

// Calculate Realized Profit using Cost Recovery Method
// First recover the cost (COGS), then profit
if ($credit_sales_cash_collected <= $credit_sales_cogs) {
    // Cost not yet fully recovered, no realized profit
    $realized_profit = 0;
} else {
    // Cost recovered, excess is realized profit
    $realized_profit = $credit_sales_cash_collected - $credit_sales_cogs;

    // Realized profit cannot exceed net profit
    $realized_profit = min($realized_profit, $credit_sales_net_profit);
}

// Calculate Unrealized Profit
// Unrealized = Total Net Profit - Realized Profit
$unrealized_profit = $credit_sales_net_profit - $realized_profit;
```

### View File Update (Optional):

**File:** `resources/views/report/partials/net_gross_profit_report_details.blade.php`

Update the help text formula display to match the new logic:

```blade
<small class="help-block">
    @if(($data['credit_sales_cash_collected'] ?? 0) <= ($data['credit_sales_cogs'] ?? 0))
        @lang('lang_v1.cash_collected') <= @lang('lang_v1.credit_sales_cogs'), @lang('lang_v1.cost_not_recovered')
    @else
        @lang('lang_v1.credit_sales_cash_collected') (<span class="display_currency" data-currency_symbol="true">{{ $data['credit_sales_cash_collected'] ?? 0 }}</span>)
        - @lang('lang_v1.credit_sales_cogs') (<span class="display_currency" data-currency_symbol="true">{{ $data['credit_sales_cogs'] ?? 0 }}</span>)
    @endif
</small>
```

---

## Summary

| Aspect | Current | Cost Recovery Method |
|--------|---------|---------------------|
| Logic | Sales - Cost - Cash = Unrealized | First recover cost, then profit |
| Negative Values | Possible | Not possible |
| Accuracy | Incorrect | Correct |
| Accounting Standard | Non-standard | Standard (Cost Recovery) |

### Final Verdict: **Implement Cost Recovery Method**

The Cost Recovery Method should be implemented as it:
1. Follows proper accounting principles
2. Prevents negative/illogical values
3. Matches the business requirement (PDF specification)
4. Is easier to understand and verify

---

## Files to Modify

1. `app/Utils/TransactionUtil.php` - Main calculation logic
2. `resources/views/report/partials/net_gross_profit_report_details.blade.php` - Display formula (optional)
3. `lang/en/lang_v1.php` - Add new translation keys if needed

---

*Analysis Date: 2026-01-22*
*Prepared for: Bebshadar POS - Profit/Loss Report Enhancement*
