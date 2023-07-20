<?php include_once '../module/main/modules.Class.php'; ?>
<div id="dsn_1" class="dsn-page">
	<div id="dsn_2" class="dsn-header">
		<button class="dsn-cl-btn"><img src="images/icons/g/16/m/menu-grid.png" alt="side menu"></button>
		<button class="dsn-cl-btn"><img src="images/icons/g/16/l/menu-grid.png" alt="side menu"></button>
	</div>
	<div id="dsn_3" class="dsn-side-menu">
		
		<!-- Element actions nav -->
		
		<div class="dsn-node-tree">
			<button id="dsn_100" class="left dsn-node-link" title="Paste element">&lt;&and;&gt;</button>
			<button id="dsn_101" class="left dsn-node-link" title="Go Next sibling up">&#8648;</button>
			<button id="dsn_102" class="left dsn-node-link" title="Go Next sibling down">&#8650;</button>
			<button id="dsn_103" class="left dsn-node-link" title="Go Parent element">[::]</button>
			<button id="dsn_104" class="left dsn-node-link" title="Go First child">&gt;::</button>
			<button id="dsn_105" class="left dsn-node-link" title="Delete selected"><img src="images/icons/g/16/l/delete.png"></button>
			<button id="dsn_106" class="left dsn-node-link" title="Copy this"><img src="images/icons/g/16/l/copy.png"></button>
		</div>
		
		<!-- Element setings and styling collapsible -->
		
		<div id="dsn_4" class="span-100 b-space-15">
		
			<!-- Element name and index -->
		
			<section id="dsn-el-classes">
				<button class="collapse dns-collapse-button">Element</button>
				<div class="collapse-content dsn-element-properties">
					<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-tag-name">Current element :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn-el-tag-name" name="dsn-el-tag-name" class="span-70" disabled></div>
					</div>
					<div class="section group b-space-10 dsn-dark-field">
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-index">Index :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn-el-index" name="dsn-el-index" class="span-20" disabled></div>
					</div>
					<div class="section group b-space-10 dsn-dark-field">
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-siblings">Siblings :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn-el-siblings" name="dsn-el-siblings" class="span-20" disabled></div>
					</div>
					<div class="section group b-space-25 dsn-dark-field">
						<div class="col-f span-50"><label for="dsn-el-child-nodes">Children :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn-el-child-nodes" name="dsn-el-child-nodes" class="span-20" disabled></div>
					</div>
				</div>
			</section>
			
			<!-- Element setings -->
						
			<section id="dsn-el-setings">
				<button class="collapse dns-collapse-button">Settings</button>
				<div class="collapse-content dsn-element-properties">
					
					<!-- Class list -->
					
					<div id="dsn-class-properties" class="space-15">
						<p>Class list :</p>
						<div id="dsn_5" class="dsn-dark-box b-space-10 pad-5">
							
						</div>
						<div class="section group dsn-attr" data-type="class" data-value="">
							<label for="dsn-el-add-class">Class :</label>
							<input type="text" placeholder="Enter class name here">
						</div>
					</div>
					
					<!-- Atributes list -->
					
					<div class="section group dsn-attr" data-type="id" data-value="">
						<label for="dsn-el-add-class">Id :</label>
						<input type="text" placeholder="id here">
					</div>
					<div class="section group dsn-attr" data-type="title" data-value="">
						<label for="dsn-el-add-class">Title :</label>
						<input type="text" placeholder="title here">
					</div>
					<div class="section group dsn-attr" data-type="name" data-value="">
						<label for="dsn-el-add-class">Name :</label>
						<input type="text" placeholder="name here">
					</div>
					<div class="section group dsn-attr" data-type="value" data-value="">
						<label for="dsn-el-add-class">Value :</label>
						<input type="text" placeholder="value here">
					</div>
					
					<!-- Custom attributes -->
					
					<section>
						<button class="collapse dsn-naked-collapse-button">Custom attributes</button>
						<div class="collapse-content dsn-element-properties">
							<p class="b-space-10 left-5">Attributes list :</p>
							<div id="dsn_6" class="dsn-dark-box b-space-10 pad-5">
								
							</div>
							<div class="section group dsn-attr" data-type="attr-name" data-value="">
								<label for="dsn-el-add-class">Attr Name :</label>
								<input type="text" placeholder="name here">
							</div>
							<div class="section group dsn-attr b-space-15" data-type="attr-value" data-value="">
								<label for="dsn-el-add-class">Attr Value :</label>
								<input type="text" placeholder="value here">
							</div>
						</div>
					</section>
				</div>
			</section>
			
			<!-- Element styling -->
			
			<section id="dsn-el-style">
			
				<!-- Element whole styling -->
			
				<button class="collapse dns-collapse-button">Style</button>
				<div class="collapse-content dsn-element-properties">
				
						<!-- General styling -->
				
						<button class="collapse dsn-naked-collapse-button">General</button>
						<div class="collapse-content dsn-naked-element-properties">
							<div class="section group span-80 c-box b-space-15 dsn-dark-field">
								<div class="col-f span-48">
									<span class="box dsn-el-prop-active t-space-10 b-space-15 indent-05" style="line-height: 1.5rem;">Float<span class="dsn-el-prop-del">×</span></span>
								</div>
								<div class="col-f span-48 left-4">
									<select class="span-100 t-space-10 b-space-15" name="dsn-el-float" id="dsn-el-float">
										<option value=""></option>
										<option value="left">left</option>
										<option value="right">right</option>
									</select>
								</div>
								<div class="col-f span-48 b-space-15">
									<span class="box dsn-el-prop-selected t-space-10 b-space-5 indent-05">Display<span class="dsn-el-prop-del">&times;</span></span>
									<select class="span-100 b-space-10" name="dsn-el-display" id="dsn-el-display">
										<option value="block">block</option>
										<option value="inline" selected>inline</option>
										<option value="inline-block">inline-block</option>
										<option value="flex">flex</option>
										<option value="none">none</option>
									</select>
									<span class="box b-space-5 indent-05">Top</span>
									<input class="span-100 b-space-10" type="text" name="dsn-el-top" placeholder="0">
									<span class="box b-space-5 indent-05">Bottom</span>
									<input class="span-100" type="text" name="dsn-el-bottom" placeholder="0">
								</div>
								<div class="col-f span-48 left-4 b-space-15">
									<span class="box t-space-10 b-space-5 indent-05">Position</span>
									<select class="span-100 b-space-10" name="dsn-el-position" id="dsn-el-position">
										<option value="static">static</option>
										<option value="relative">relative</option>
										<option value="fixed">fixed</option>
										<option value="absolute">absolute</option>
										<option value="sticky">sticky</option>
									</select>
									<span class="box b-space-5 indent-05">Left</span>
									<input class="span-100 b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<span class="box b-space-5 indent-05">Right</span>
									<input class="span-100" type="text" name="dsn-el-right" placeholder="0">
								</div>
							</div>
						</div>
						
						<!-- Dimensions styling -->
						
						<button class="collapse dsn-naked-collapse-button">Dimensions</button>
						<div class="collapse-content dsn-element-properties">
							<div class="dsn-90-box c-box section group t-space-10 b-space-15">
								<div class="col-f span-48 b-space-15">
									<span class="col-f span-100 dsn-el-prop-active t-space-10 b-space-5 indent-05">Width<span class="dsn-el-prop-del">&times;</span></span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Min width</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Max width</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								
								<div class="col-f span-48 left-4 b-space-15">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Min height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Max height</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Spacing styling -->

						<button class="collapse dsn-naked-collapse-button">Spacing</button>
						<div class="collapse-content dsn-element-properties">
							<span class="box left-10 dsn-el-prop-active t-space-15 b-space-5">Margin<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-15">
								<div class="col-f span-48 b-space-15 t-space-5">
									<span class="col-f span-100 b-space-5 indent-05">Margin left</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Margin top</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4 t-space-5 b-space-15">
									<span class="col-f b-space-5 indent-05">Margin right</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Margin bottom</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
							
							<span class="box left-10 b-space-5 t-space-10">Padding</span>
							<div class="dsn-90-box c-box option group b-space-15">
								<div class="col-f span-48 b-space-15 t-space-10">
									<span class="col-f span-100 b-space-5 indent-05">Padding left</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Padding top</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4 b-space-15 t-space-10">
									<span class="col-f span-100 b-space-5 indent-05">Padding right</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Padding bottom</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
						</div>
					
						<!-- Typography styling -->
					
						<button class="collapse dsn-naked-collapse-button">Typography</button>
						<div class="collapse-content dsn-element-properties">
							<div class="dsn-90-box c-box section group space-15">
								<div class="col-f span-48 b-space-15">
									<span class="box span-100 dsn-el-prop-active indent-05" style="line-height: 1.5rem;">Text align<span class="dsn-el-prop-del">×</span></span>
								</div>
								<div class="col-f span-48 left-4 b-space-15">
									<select class="box span-100" name="dsn-el-float" id="dsn-el-float">
										<option value="left">left</option>
										<option value="right">right</option>
										<option value="center">center</option>
										<option value="justify">justify</option>
									</select>
								</div>
								<div class="col-f span-48 b-space-15">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Font size</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Font Family</span>
									<select class="span-100 b-space-10" name="dsn-el-font-family" id="dsn-el-font-family">
										<option value="Arial, sans-serif">Arial</option>
										<option value="Verdana, sans-serif">Verdana</option>
										<option value="Times New Roman, serif">Times New Roman</option>
										<option value="Tahoma, sans-serif">Tahoma</option>
										<option value="Georgia, serif">Georgia</option>
										<option value="Garamond, serif">Garamond</option>
										<option value="courier new, monospace">Courier New</option>
										<option value="Brush Script MT, cursive">Brush Script MT</option>
										<option value="Helvetica">Helvetica</option>
										<option value="Impact">Impact</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Font Weight</span>
									<select class="span-100" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="100">Thin (Hairline)</option>
										<option value="200">Extra Light (Ultra Light)</option>
										<option value="300">Light</option>
										<option value="400">Normal (Regular)</option>
										<option value="500">Medium</option>
										<option value="600">Semi Bold (Demi Bold)</option>
										<option value="700">Bold</option>
										<option value="800">Extra Bold (Ultra Bold)</option>
										<option value="900">Black (Heavy)</option>
									</select>
								</div>
								
								<div class="col-f span-48 b-space-15 left-4">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Line height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6 col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Letter spacing</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Color</span>
									<input class="span-4-of-6-f col-f" type="text" style="display:inline;" name="dsn-el-right" value="#c99a16">
									<input class="span-2-of-6-f col-f" type="color" style="display:inline;" name="dsn-el-right" value="#c99a16">
								</div>
							</div>
						</div>
					
						<!-- Decorations styling -->
					
						<button class="collapse dsn-naked-collapse-button">Decoration</button>
						<div class="collapse-content dsn-element-properties">
							
							<!-- Opacity -->
							
							<div class="span-80 c-box option group space-15">
								<div class="col-f b-space-15 span-100">
									<span class="col-f pad-5 span-80">Opacity</span>
									<input class="box span-20 right" type="text" placeholder="0.5">
								</div>
								<input type="range" min="1" max="100" value="50" class="dsn-slider" id="myRange">
							</div>
							
							<!-- Borders -->
							
							<span class="box span-100 dsn-el-prop-active t-space-10 b-space-5 left-5 indent">Borders<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<div class="col-f span-48">
									<span class="col-f span-100 b-space-5 indent-05">Width</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4">
									<span class="col-f span-100 b-space-5 indent-05">Style</span>
									<select class="span-100 col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="none">none</option>
										<option value="solid">solid</option>
										<option value="inherit">inherit</option>
										<option value="hidden">hidden</option>
										<option value="dotted">dotted</option>
										<option value="dashed">dashed</option>
										<option value="double">double</option>
										<option value="groove">groove</option>
										<option value="ridge">ridge</option>
										<option value="inset">inset</option>
										<option value="outset">outset</option>
										<option value="initial">initial</option>
									</select>
								</div>
								<span class="col-f span-100 b-space-5 indent-05">Color</span>
								<div class="span-100 col-f">
									<input class="span-6-of-7-f col-f" type="text" name="dsn-el-right" value="#c99a16">
									<input class="span-1-of-7-f col-f" type="color" name="dsn-el-right" value="#c99a16">
								</div>
							</div>
							
							<!-- Border radius -->
							
							<span class="line-box dsn-el-prop-selected t-space-10 b-space-5 left-5 indent">Border radius<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<div class="col-f span-48">
									<span class="col-f span-100 b-space-5 indent-05">Top left</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Bottom left</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4">
									<span class="col-f span-100 b-space-5 indent-05">Top right</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Bottom Right</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
							
							<!-- Background -->
							
							<span class="line-box dsn-el-prop-selected t-space-10 b-space-5 left-5 indent">Backgound<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<span class="col-f span-100 b-space-5 indent-05">Background Color</span>
								<div class="span-100 col-f b-space-10">
									<input class="span-6-of-7-f col-f" type="text" style="display:inline;" name="dsn-el-right" value="#c99a16">
									<input class="span-1-of-7-f col-f" type="color" style="display:inline;" name="dsn-el-right" value="#c99a16">
								</div>
								<span class="col-f span-100 b-space-5 indent-05">Background Image</span>
								<div class="span-100 col-f">
									<input class="span-100 left" type="text" style="display:inline;" name="dsn-el-right" placeholder="url('img_tree.gif')">
								</div>
							</div>
						</div>
						
						<!-- Other styling -->
					
						<button class="collapse dsn-naked-collapse-button">Other</button>
						<div class="collapse-content dsn-element-properties">
							
						</div>
						
						<!-- Flex styling -->
					
						<button class="collapse dsn-naked-collapse-button">Flex</button>
						<div class="collapse-content dsn-element-properties">
							
						</div>
					
					<div class="dsn-element-properties-last"></div>
				</div>
			</section>
		</div>
	</div>
	<div id="dsn_5" class="dsn-body" contenteditable="true">
		<div>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
		</div>
		<div>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
		</div>
	</div>
</div>