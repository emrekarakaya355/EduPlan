<div class="course-section">
    <div class="table-header">
        <!-- Search ve Add Button -->
        <input type="text" placeholder="Ders arayın..." class="search-input">
        <button class="add-button">+</button>
    </div>
    <livewire:courses.compact-list :courses="$courses"/>

    <style>
        .course-section {
            padding: 10px;
            overflow-y: auto;,
        }
    </style>
</div>
