# 🛒 Sistema de Ventas – Refined Data Model

## 🧭 Conventions
- Tables and columns use **snake_case**, in **singular**.  
- Primary keys are **UUID** generated with `gen_random_uuid()` (`CREATE EXTENSION pgcrypto;`).  
- 🕵️ **Audit fields (7):**  
  - `status`  
  - `created_at` / `created_by`  
  - `updated_at` / `updated_by`  
  - `deleted_at` / `deleted_by`  
- Foreign Keys use **ON UPDATE CASCADE** and **ON DELETE** rules appropriate to the domain.  
- 🆔 **Unique constraints** on codes and names (scoped where applicable).  

---

## 📦 Module: Product Management

**product_category**  
{id, code, name, description}  

- `code` (VARCHAR, UNIQUE)  
- `name` (VARCHAR)  
- `description` (TEXT, nullable)  

**product**  
{id, code, name, description, price, stock, product_category_id}  

- `code` (VARCHAR, UNIQUE)  
- `name` (VARCHAR)  
- `description` (TEXT, nullable)  
- `price` (DECIMAL(10,2))  
- `stock` (INTEGER)  
- `product_category_id` (UUID, FK)  

---

## 🧾 Module: Sale Management

**sale_status**  
{id, code, name, description}  

- `code` (VARCHAR, UNIQUE)  
- `name` (VARCHAR)  
- `description` (TEXT, nullable)  

**sale**  
{id, sale_date, total_amount, sale_status_id}  

- `sale_date` (TIMESTAMP)  
- `total_amount` (DECIMAL(12,2))  
- `sale_status_id` (UUID, FK)  

**sale_detail**  
{id, sale_id, product_id, quantity, unit_price, subtotal}  

- `quantity` (INTEGER)  
- `unit_price` (DECIMAL(10,2))  
- `subtotal` (DECIMAL(12,2))  

---

### 🔗 Relationships
- `product.product_category_id` → **product_category.id**  
- `sale.sale_status_id` → **sale_status.id**  
- `sale_detail.sale_id` → **sale.id**  
- `sale_detail.product_id` → **product.id**  

---

### ⚙️ Referential Actions
- **ON UPDATE CASCADE** in all foreign keys.  
- **ON DELETE RESTRICT** for critical entities (`product`, `sale`).  
- **ON DELETE CASCADE** for dependent entities (`sale_detail`).  

