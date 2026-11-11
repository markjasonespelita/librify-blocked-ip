// Samples

// localstorage_read(localstorage_name)
// localstorage_insert(localstorage_name, inserted_data)
// localstorage_insert_many(localstorage_name, data_array)
// localstorage_update(localstorage_name, key, value, new_key, new_value)
// localstorage_update_many(localstorage_name, where_key, where_value, updates = {})
// localstorage_delete(localstorage_name, predicate)
// localstorage_delete_many(localstorage_name, predicate)
// localstorage_clear(localstorage_name)

// localstorage_where(localstorage_name, predicate)
// localstorage_first(localstorage_name, predicate)
// localstorage_find(localstorage_name, key, value)
// localstorage_exists(localstorage_name, key, value)
// localstorage_count(localstorage_name)
// localstorage_order_by(localstorage_name, key, direction = "asc")
// localstorage_limit(localstorage_name, limit, offset = 0)
// localstorage_paginate(localstorage_name, page = 1, per_page = 10)


/* =========================================================
   LOCAL STORAGE CRUD & QUERY FUNCTIONS
   Data structure: ARRAY OF OBJECTS
   SQL-like fields:
   id, created_at, updated_at
   ========================================================= */


/* -----------------------------
   INTERNAL: GENERATE UNIQUE ID
   ----------------------------- */
function localstorage_generate_id() {
    return (
        Date.now().toString(36) +
        Math.random().toString(36).substr(2, 9)
    );
}

/*
USAGE:
const id = localstorage_generate_id();
*/


/* -----------------------------
   INTERNAL: CURRENT TIMESTAMP
   ----------------------------- */
function localstorage_now() {
    return new Date().toISOString();
}

/*
USAGE:
const time = localstorage_now();
*/


/* -----------------------------
   READ ALL DATA
   ----------------------------- */
function localstorage_read(localstorage_name) {
    const data = localStorage.getItem(localstorage_name);
    return data ? JSON.parse(data) : [];
}

/*
USAGE:
const users = localstorage_read("users");
console.log(users);
*/


/* -----------------------------
   INSERT (AUTO ID + TIMESTAMPS)
   ----------------------------- */
function localstorage_insert(localstorage_name, inserted_data) {
    let storage = localstorage_read(localstorage_name);

    const record = {
        id: localstorage_generate_id(),
        created_at: localstorage_now(),
        updated_at: localstorage_now(),
        ...inserted_data
    };

    storage.push(record);
    localStorage.setItem(localstorage_name, JSON.stringify(storage));
    return record;
}

/*
USAGE:
localstorage_insert("users", { name: "Mark", role: "Admin" });
*/


/* -----------------------------
   INSERT MANY
   ----------------------------- */
function localstorage_insert_many(localstorage_name, data_array) {
    let storage = localstorage_read(localstorage_name);

    const records = data_array.map(item => ({
        id: localstorage_generate_id(),
        created_at: localstorage_now(),
        updated_at: localstorage_now(),
        ...item
    }));

    storage = storage.concat(records);
    localStorage.setItem(localstorage_name, JSON.stringify(storage));
    return records;
}

/*
USAGE:
localstorage_insert_many("users", [
    { name: "John" },
    { name: "Anna" }
]);
*/


/* -----------------------------
   UPDATE (SINGLE FIELD)
   ----------------------------- */
function localstorage_update(localstorage_name, key, value, new_key, new_value) {
    let storage = localstorage_read(localstorage_name);

    storage.forEach(item => {
        if (item[key] === value) {
            item[new_key] = new_value;
            item.updated_at = localstorage_now();
        }
    });

    localStorage.setItem(localstorage_name, JSON.stringify(storage));
    return true;
}

/*
USAGE:
localstorage_update("users", "id", someId, "name", "Mark Jason");
*/


/* -----------------------------
   UPDATE (MULTIPLE FIELDS)
   ----------------------------- */
function localstorage_update_many(localstorage_name, where_key, where_value, updates = {}) {
    let storage = localstorage_read(localstorage_name);

    storage.forEach(item => {
        if (item[where_key] === where_value) {
            Object.keys(updates).forEach(k => {
                item[k] = updates[k];
            });
            item.updated_at = localstorage_now();
        }
    });

    localStorage.setItem(localstorage_name, JSON.stringify(storage));
    return true;
}

/*
USAGE:
localstorage_update_many("users", "id", someId, {
    name: "Mark Jason",
    role: "Super Admin"
});
*/


/* -----------------------------
   DELETE (FIRST MATCH)
   ----------------------------- */
function localstorage_delete(localstorage_name, predicate) {
    let storage = localstorage_read(localstorage_name);
    const index = storage.findIndex(predicate);

    if (index !== -1) {
        storage.splice(index, 1);
        localStorage.setItem(localstorage_name, JSON.stringify(storage));
        return true;
    }
    return false;
}

/*
USAGE:
localstorage_delete("users", u => u.id === someId);
*/


/* -----------------------------
   DELETE MANY
   ----------------------------- */
function localstorage_delete_many(localstorage_name, predicate) {
    let storage = localstorage_read(localstorage_name);
    storage = storage.filter(item => !predicate(item));
    localStorage.setItem(localstorage_name, JSON.stringify(storage));
    return true;
}

/*
USAGE:
localstorage_delete_many("users", u => u.role === "Guest");
*/


/* -----------------------------
   DELETE ALL DATA
   ----------------------------- */
function localstorage_clear(localstorage_name) {
    localStorage.removeItem(localstorage_name);
    return true;
}

/*
USAGE:
localstorage_clear("users");
*/


/* =========================================================
   QUERY FUNCTIONS
   ========================================================= */


/* -----------------------------
   WHERE (FILTER)
   ----------------------------- */
function localstorage_where(localstorage_name, predicate) {
    return localstorage_read(localstorage_name).filter(predicate);
}

/*
USAGE:
const admins = localstorage_where("users", u => u.role === "Admin");
*/


/* -----------------------------
   FIND FIRST MATCH
   ----------------------------- */
function localstorage_first(localstorage_name, predicate) {
    return localstorage_read(localstorage_name).find(predicate) || null;
}

/*
USAGE:
const user = localstorage_first("users", u => u.name === "Mark");
*/


/* -----------------------------
   FIND BY KEY = VALUE
   ----------------------------- */
function localstorage_find(localstorage_name, key, value) {
    return localstorage_read(localstorage_name)
        .find(item => item[key] === value) || null;
}

/*
USAGE:
const user = localstorage_find("users", "id", someId);
*/


/* -----------------------------
   EXISTS CHECK
   ----------------------------- */
function localstorage_exists(localstorage_name, key, value) {
    return localstorage_read(localstorage_name)
        .some(item => item[key] === value);
}

/*
USAGE:
if (localstorage_exists("users", "email", "mark@test.com")) {
    console.log("Email already exists");
}
*/


/* -----------------------------
   COUNT RECORDS
   ----------------------------- */
function localstorage_count(localstorage_name) {
    return localstorage_read(localstorage_name).length;
}

/*
USAGE:
const totalUsers = localstorage_count("users");
*/


/* -----------------------------
   ORDER BY
   ----------------------------- */
function localstorage_order_by(localstorage_name, key, direction = "asc") {
    return localstorage_read(localstorage_name).sort((a, b) => {
        if (a[key] < b[key]) return direction === "asc" ? -1 : 1;
        if (a[key] > b[key]) return direction === "asc" ? 1 : -1;
        return 0;
    });
}

/*
USAGE:
const usersByDate = localstorage_order_by("users", "created_at", "desc");
*/


/* -----------------------------
   LIMIT & OFFSET
   ----------------------------- */
function localstorage_limit(localstorage_name, limit, offset = 0) {
    return localstorage_read(localstorage_name)
        .slice(offset, offset + limit);
}

/*
USAGE:
const firstFive = localstorage_limit("users", 5);
*/


/* -----------------------------
   PAGINATION
   ----------------------------- */
function localstorage_paginate(localstorage_name, page = 1, per_page = 10) {
    const storage = localstorage_read(localstorage_name);
    const start = (page - 1) * per_page;

    return {
        data: storage.slice(start, start + per_page),
        page,
        per_page,
        total: storage.length,
        total_pages: Math.ceil(storage.length / per_page)
    };
}

/*
USAGE:
const result = localstorage_paginate("users", 1, 10);
console.log(result.data);
*/
