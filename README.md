# Goru Pagla Connectors

Developed for the **Web Engineering Lab** project course.

A small web app where people can see upcoming **livestock market events** (cattle markets / “gorur haat”) in one place.

## What it does

The site publishes **events** with title, location, date/time, and description. Visitors browse what is coming up near them. Staff use an **admin** area to create and maintain those listings.

## Main frontend (public)

- **Route:** `/` (home).
- Tailwind-based layout: header with app branding and a responsive **grid** of event cards (two per row on larger screens, one column on phones).
- Each card shows event details plus **Going** and **Interested** actions that update counters (one response per browser session).

No login is required to use the home page.

## Admin

- **Access:** admins sign in (`/login`). The **dashboard** (`/dashboard`) sends them to the admin event list.
- **Area:** `/admin/events` — list, create, view, edit, and delete events (standard CRUD).
- Events support fields such as scheduling, location, publish state, and auto-generated URL slugs from the title.

Only users marked as admins can reach these routes.

## Features

- Public **upcoming events** list (published events only).
- **Going** / **Interested** counts with session-based visitor choice.
- **Admin** event management: create, update, delete, publish control, pagination on the list.
- **Authentication** for admins; **profile** routes for signed-in users (Laravel Breeze–style flow).

---

Stack: **Laravel**, **Blade**, **Tailwind CSS**, **Vite**.
