<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Payment Form</h1>

        <form action="" method="POST">
            <!-- Payment Type Section -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Payment Type</h2>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="payment_type" value="cash" class="form-radio h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">Cash</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_type" value="installment" class="form-radio h-4 w-4 text-blue-600">
                        <span class="ml-2 text-gray-700">Installment</span>
                    </label>
                </div>
            </div>

            <!-- Fee Sections with Amount Inputs -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Fees</h2>
                <div class="space-y-4">
                    <!-- Input fields for each fee type -->
                    <div class="flex items-center space-x-2">
                        <label for="amount_tuition_fee" class="text-gray-700 w-1/3">Tuition Fee</label>
                        <input type="number" id="amount_tuition_fee" name="amount_tuition_fee" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <label for="amount_misc_fee" class="text-gray-700 w-1/3">Misc. Fee</label>
                        <input type="number" id="amount_misc_fee" name="amount_misc_fee" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_bank_account" class="text-gray-700 w-1/3">Bank Account</label>
                        <input type="number" id="amount_bank_account" name="amount_bank_account" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_research_fee" class="text-gray-700 w-1/3">Research Fee</label>
                        <input type="number" id="amount_research_fee" name="amount_research_fee" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_transfer_fee" class="text-gray-700 w-1/3">Transfer Fee</label>
                        <input type="number" id="amount_transfer_fee" name="amount_transfer_fee" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_overload" class="text-gray-700 w-1/3">Overload</label>
                        <input type="number" id="amount_overload" name="amount_overload" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_installment_dp" class="text-gray-700 w-1/3">Installment (DP)</label>
                        <input type="number" id="amount_installment_dp" name="amount_installment_dp" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label for="amount_total" class="text-gray-700 w-1/3">Total</label>
                        <input type="number" id="amount_total" name="amount_total" placeholder="Amount" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-32">
                    </div>
                </div>
            </div>

            <!-- Assessed By Section -->
            <div class="mb-4">
                <label for="assessed_by" class="block text-xl font-semibold mb-2">Assessed By</label>
                <input type="text" id="assessed_by" name="assessed_by" class="border-b-2 border-gray-300 focus:border-blue-500 focus:outline-none p-1 w-full" placeholder="Enter name">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Submit
            </button>
        </form>
    </div>

</body>
</html>
