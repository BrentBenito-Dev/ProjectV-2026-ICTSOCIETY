<div class="min-h-screen bg-gray-50 py-12 px-4">
  <div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Add a Confession</h2>
    
    <form action="add-confession.php" method="POST" class="space-y-4">
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input type="text" name="first_name" required 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input type="text" name="last_name" required 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Custom Clue</label>
          <input type="text" name="custom_clue" 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Your Section</label>
          <input type="text" name="section" 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Message (Max 100 characters)</label>
        <textarea name="message" maxlength="100" rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition resize-none"
                  placeholder="Spill the tea..."></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Name</label>
        <input type="text" name="recipient_name" 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition">
      </div>

      <div class="pt-4">
        <button type="submit" 
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3 px-4 rounded-lg shadow-sm transition-colors duration-200">
          Confess Now!
        </button>
      </div>
      
    </form>
  </div>
</div>