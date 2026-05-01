<!DOCTYPE html>
<html>

<head>
    <title>COBOL to API Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        .upload-zone {
            border: 3px dashed #667eea;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #f8f9ff;
            transition: all 0.3s;
        }

        .upload-zone:hover {
            background: #eef1ff;
            border-color: #764ba2;
        }

        input[type="file"] {
            display: none;
        }

        .file-label {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .file-label:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button {
            padding: 12px 30px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            transition: all 0.3s;
        }

        button:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-copy {
            background: #007bff;
            margin-top: 10px;
        }

        .btn-copy:hover {
            background: #0056b3;
        }

        pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            font-size: 0.9em;
            line-height: 1.6;
            max-height: 500px;
            overflow-y: auto;
        }

        .error {
            background: #fee;
            padding: 15px;
            border-radius: 8px;
            color: #c00;
            margin-bottom: 15px;
            border-left: 4px solid #c00;
        }

        .section {
            margin-bottom: 25px;
        }

        .test-form input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border 0.3s;
        }

        .test-form input:focus {
            outline: none;
            border-color: #667eea;
        }

        .result-box {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin-top: 15px;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-right: 8px;
        }

        .badge-add {
            background: #d4edda;
            color: #155724;
        }

        .badge-subtract {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 968px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1> COBOL to API Generator</h1>

        <div class="grid">
            <!-- Left Column: Upload & Parsed Operations -->
            <div>
                <div class="card">
                    <h2>📤 Upload COBOL File</h2>

                    <?php if($errors->any()): ?>
                        <div class="error">
                            <strong>Error:</strong>
                            <ul style="margin: 5px 0 0 20px;">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/generate" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="upload-zone">
                            <label for="cobol_file" class="file-label">Choose COBOL File</label>
                            <input type="file" id="cobol_file" name="cobol_file" required accept=".cbl,.cob,.txt">
                            <p style="margin-top: 15px; color: #666;">Supported: .cbl, .cob, .txt</p>
                        </div>
                        <button type="submit" style="width: 100%; margin-top: 20px;">Generate API</button>
                    </form>
                </div>

                <?php if(isset($result)): ?>
                    <div class="card" style="margin-top: 20px;">
                        <h2>🔍 Parsed COBOL Operations</h2>
                        <div class="section">
                            <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="padding: 10px; margin: 8px 0; background: #f8f9ff; border-radius: 6px;">
                                    <span class="badge badge-<?php echo e($op['type']); ?>"><?php echo e(strtoupper($op['type'])); ?></span>
                                    <strong><?php echo e($op['from']); ?></strong> → <strong><?php echo e($op['to']); ?></strong>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <pre><?php echo e(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Generated Code & Test -->
            <div>
                <?php if(isset($ai_code)): ?>
                    <div class="card">
                        <h2>⚡ Generated Laravel API</h2>
                        <pre><?php echo e($ai_code); ?></pre>
                        <button class="btn-copy" onclick="copyCode()">📋 Copy Code</button>
                    </div>
                <?php endif; ?>

                <?php if(isset($result)): ?>
                    <div class="card" style="margin-top: 20px;">
                        <h2>🧪 Test API Live</h2>
                        <form class="test-form" id="testForm">
                            <?php echo csrf_field(); ?>
                            <?php
                                $fields = [];
                                foreach ($result as $op) {
                                    $fields[$op['from']] = true;
                                }
                            ?>

                            <?php $__currentLoopData = array_keys($fields); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label style="display: block; margin-top: 12px; color: #667eea; font-weight: bold; font-size: 0.95em;">
                                    <?php echo e($field); ?>:
                                </label>
                                <input type="number" step="any" name="<?php echo e(strtolower($field)); ?>"
                                    placeholder="Enter <?php echo e($field); ?>" value="100">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <button type="submit" style="width: 100%; margin-top: 15px;">▶️ Run Test</button>
                        </form>

                        <div id="testResult"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function copyCode() {
            const code = document.querySelector('pre').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert('✅ Code copied to clipboard!');
            });
        }

        <?php if(isset($result)): ?>
            document.getElementById('testForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                try {
                    const response = await fetch('/cobol/test', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    document.getElementById('testResult').innerHTML = `
            <div class="result-box">
                <h4 style="color: #28a745; margin-bottom: 10px;">✅ Test Result:</h4>
                <pre style="background: #1e1e1e; color: #4ec9b0;">${JSON.stringify(data, null, 2)}</pre>
            </div>
        `;
                } catch (error) {
                    document.getElementById('testResult').innerHTML = `
            <div class="error">❌ Error: ${error.message}</div>
        `;
                }
            });
        <?php endif; ?>

        // Show selected filename
        document.getElementById('cobol_file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.querySelector('.file-label').textContent = '✓ ' + fileName;
            }
        });
    </script>

</body>

</html>
<?php /**PATH C:\Users\jdira\Herd\smart-cobol-api\resources\views/main.blade.php ENDPATH**/ ?>