# Admin Input RSpec

## Goal
Verify all admin input components in Filament resources behave consistently, save correctly, and use the current Filament namespace/API.

## Scope
- Resource forms
- Page settings forms
- Upload inputs
- Text inputs
- Selects / toggles / checkboxes
- SEO inputs
- Image preview inputs

## Functional requirements

### 1. Banner form
Fields:
- key
- title
- subtitle
- image
- link
- button_text
- size_label
- recommended_size
- sort_order
- is_active

Rules:
- `key` is required and unique enough to identify a banner slot
- `image` accepts a path or upload and saves successfully
- `sort_order` is numeric
- `is_active` defaults to true
- preview hint is shown to admin

### 2. Site settings form
Fields:
- site_title
- site_description
- site_logo_type
- site_logo_height
- site_logo_width
- site_logo upload
- site_logo_dark upload
- site_logo_mobile upload
- site_favicon upload
- home_hero_image upload
- SEO defaults

Rules:
- upload fields must hydrate without breaking Livewire synthesizers
- upload fields must persist path strings to DB
- re-opening the form must preserve uploaded values
- preview must show current images safely

### 3. Tag form
Fields:
- name
- slug
- type

Rules:
- changing name on create should auto-slugify
- slug is unique
- type defaults to valid enum values

### 4. Menu form
Fields:
- location
- label
- url
- route_name
- target
- parent_id
- sort_order
- icon
- css_class
- meta_config
- is_active

Rules:
- `location` and `label` are required
- parent menu selection must work with current Filament relationship API
- boolean fields persist correctly

### 5. Page/Post/Product/Category forms
Common rules:
- current Filament `Schema` components are used
- current `Grid` / `Section` namespaces are used
- SEO fields are editable where supported
- uploads and relationship fields do not throw type errors
- forms validate required fields before save

## Non-functional requirements
- No deprecated Filament form namespaces
- No `Forms\Set` type mismatches
- No `FileUpload` state synthesizer errors
- No array-to-string conversion when saving uploaded file paths
- SEO previews render without runtime errors

## Acceptance criteria
- Each admin form opens without exceptions
- Uploads save and persist across reloads
- All inputs use the current Filament version's API
- Lint and runtime checks pass for resources in `app/Filament/Resources`
